<?php

namespace Tests\Unit\Models;

use App\Components\Decoder\DecoderInterface;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class UrlTest extends TestCase
{
    use WithFaker;

    protected Url $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->mock(DecoderInterface::class, function ($mock) {
            $mock->shouldReceive('encode')->andReturn('code');
        });

        $this->model = Url::factory()->make();
        $this->model->id = 999;
    }

    public function testGetCode()
    {
        $this->assertEquals('code', $this->model->getCode());
    }

    public function testToArray()
    {
        Config::set('app.url', 'http://test.domain');
        $data = $this->model->toArray();

        $this->assertNotEmpty($data['short_code']);
        $this->assertNotEmpty($data['short_link']);

        $this->assertEquals('code', $data['short_code']);
        $this->assertEquals('http://test.domain/code', $data['short_link']);
    }

    /**
     * @dataProvider expiresAtData
     */
    public function testIsExpired(?Carbon $date, bool $expected)
    {
        $this->model->expires_at = $date;

        $this->assertEquals($expected, $this->model->isExpired());
    }

    public function expiresAtData(): array
    {
        return [
            [Carbon::now()->subDay(), true],
            [Carbon::now()->addWeek(), false],
            [null, false],
        ];
    }
}
