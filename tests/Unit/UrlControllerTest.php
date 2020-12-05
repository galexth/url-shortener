<?php

namespace Tests\Unit;

use App\Models\Url;
use App\Repositories\UrlRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use WithFaker;

    /**
     * @param array $request
     * @param array $expected
     * @dataProvider indexProvider
     */
    public function testIndex(array $request, array $expected)
    {
        $this->mock(UrlRepositoryInterface::class, function ($mock) use ($request, $expected) {
            $mock->shouldReceive('find->paginate')
                ->andReturn(new LengthAwarePaginator($expected['data'], $expected['total'], $request['pageSize']));
        });

        $response = $this->postJson('/api/urls/search', );

        $response->assertJsonFragment($expected);
    }

    public function testStoreExisting()
    {
        $url = Url::factory()->make();
        $url->expires_at = Carbon::tomorrow();
        $url->id = 1;

        $this->mock(UrlRepositoryInterface::class, function ($mock) use ($url) {
            $mock->shouldReceive('findByUrl')->andReturn($url);
        });

        $response = $this->postJson('/api/urls', [
            'url' => $url->url,
            'expires_at' => $url->expires_at->toDateTimeString(),
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment($url->toArray());
    }

    public function testStoreNew()
    {
        $url = Url::factory()->make();
        $url->expires_at = Carbon::tomorrow();
        $url->wasRecentlyCreated = true;
        $url->id = 1;

        $this->mock(UrlRepositoryInterface::class, function ($mock) use ($url) {
            $mock->shouldReceive('findByUrl')->andReturn(null);
            $mock->shouldReceive('create')->andReturn($url);
        });

        $response = $this->postJson('/api/urls', [
            'url' => $url->url,
            'expires_at' => $url->expires_at->toDateTimeString(),
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment($url->toArray());
    }

    public function testStoreInvalid()
    {
        $response = $this->postJson('/api/urls', [
            'url' => 'invalid url',
            'expires_at' => Carbon::yesterday()->toDateTimeString(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'url', 'expires_at'
            ]
        ]);
    }

    public function testDestroy()
    {
        $mock = $this->mock(UrlRepositoryInterface::class);
        $mock->shouldReceive('findById')->andReturn(new Url());
        $mock->shouldReceive('delete')->andReturn(true);

        $response = $this->delete('/api/urls/1');

        $response->assertStatus(200);
        $response->assertExactJson([
            'deleted' => true
        ]);
    }

    public function indexProvider(): array
    {
        return [
            [
                [
                    'page' => 1,
                    'pageSize' => 12,
                    'filters' => [
                        'short_code' => 'abc',
                        'url' => 'fragment',
                    ],
                    'sortBy' => [
                        [
                            'id' => 'hits',
                            'desc' => true,
                        ],
                        [
                            'id' => 'expires_at',
                            'desc' => false,
                        ]
                    ]
                ],
                [
                    'data' => [
                        [
                            'id' => 1,
                            'url' => 'http://url1',
                            'short_code' => 'abc',
                            'short_link' => 'http://url1/abc',
                            'expires_at' => Carbon::now()->toDateTimeString(),
                        ],
                        [
                            'id' => 2,
                            'url' => 'http://url2',
                            'short_code' => 'abcd',
                            'short_link' => 'http://url1/abcd',
                            'expires_at' => Carbon::now()->toDateTimeString(),
                        ]
                    ],
                    'last_page' => 1,
                    'per_page' => 12,
                    'total' => 2,
                ]
            ]
        ];
    }
}
