<?php

namespace Tests\Unit;

use App\Components\Decoder\DecoderInterface;
use App\Models\Url;
use App\Repositories\UrlRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UrlRepositoryTest extends TestCase
{
    use WithFaker;

    protected $model;

    private UrlRepository $repository;

    private $decoder;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = $this->mock(Url::class);

        $this->decoder = $this->mock(DecoderInterface::class);
        $this->decoder->shouldReceive('encode')->andReturn('code');
        $this->decoder->shouldReceive('decode')->andReturn(1);

        $this->repository = new UrlRepository($this->model, $this->decoder);
    }

    public function testFindById()
    {
        $url = new Url();
        $url->id = 1;

        $this->model->shouldReceive('query->find')
            ->andReturn($url);

        $url = $this->repository->findById(1);

        $this->assertEquals(1, $url->id);
    }

    public function testFindByCode()
    {
        $url = new Url();
        $url->id = 1;

        Cache::shouldReceive('get')->andReturn($url);
        Cache::shouldReceive('put')->andReturnTrue();

        $this->model->shouldReceive('query->find')
            ->andReturn($url);

        $url = $this->repository->findByCode('code');

        $this->assertEquals(1, $url->id);
    }

    public function testFindByUrl()
    {
        $url = new Url();
        $url->url = $this->faker->url;
        $url->id = 1;

        $this->model
            ->shouldReceive('query')
            ->andReturnSelf()
            ->shouldReceive('where')->once()->with('url', $url->url)
            ->andReturnSelf()
            ->shouldReceive('first')
            ->andReturn($url);

        $url = $this->repository->findByUrl($url->url);

        $this->assertEquals(1, $url->id);

        $this->model
            ->shouldReceive('query')
            ->andReturnSelf()
            ->shouldReceive('where')->once()->with('url', $url->url)
            ->andReturnSelf()
            ->shouldReceive('where')->once()
            ->andReturnSelf()
            ->shouldReceive('first')
            ->andReturn($url);

        $url = $this->repository->findByUrl($url->url, true);

        $this->assertEquals(1, $url->id);
    }

    public function testFind()
    {
        $filter = [
            'short_code' => 'code',
            'url' => 'http://url',
        ];

        $sort = [
            [
                'id' => 'hits',
                'desc' => true,
            ],
        ];

        $query = Url::query();

        $this->model->shouldReceive('query')->andReturn($query);

        $this->assertEquals($query, $this->repository->find($filter, $sort));
    }

    public function testDelete()
    {
        $this->model
            ->shouldReceive('getAttribute')
            ->with('short_code')
            ->andReturn('code')
            ->shouldReceive('delete')->andReturnTrue();

        Cache::shouldReceive('forget')->andReturnTrue();

        $this->assertEquals(true, $this->repository->delete($this->model));
    }

    public function testCreate()
    {
        $data = [
            'url' => 'http://url',
            'expires_at' => Carbon::now(),
        ];

        $url = new Url($data);

        $this->model
            ->shouldReceive('query->create')
            ->with($data)
            ->andReturn($url);

        $this->assertEquals($url, $this->repository->create($data));
    }
}
