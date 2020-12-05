<?php

namespace Tests\Unit;

use App\Models\Url;
use App\Repositories\UrlRepositoryInterface;
use Tests\TestCase;

class RedirectControllerTest extends TestCase
{
    public function testForward()
    {
        $mockModel = $this->mock(Url::class);
        $mockModel->shouldAllowMockingProtectedMethods();
        $mockModel->shouldReceive('increment')->andReturn(true);
        $mockModel->shouldReceive('getAttribute')->andReturn('test');
        $mockModel->shouldReceive('isExpired')->andReturn(false);

        $this->mock(UrlRepositoryInterface::class, function ($mock) use ($mockModel) {
            $mock->shouldReceive('findByCode')->andReturn($mockModel);
        });

        $response = $this->get('/code');
        $response->assertRedirect('test');
    }

    public function testForwardExpired()
    {
        $mockModel = $this->mock(Url::class);
        $mockModel->shouldReceive('isExpired')->andReturn(true);

        $this->mock(UrlRepositoryInterface::class, function ($mock) use ($mockModel) {
            $mock->shouldReceive('findByCode')->andReturn($mockModel);
        });

        $response = $this->get('/code');
        $response->assertStatus(410);
    }

    public function testForwardNotFound()
    {
        $this->mock(UrlRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('findByCode')->andReturn(null);
        });

        $response = $this->get('/code');
        $response->assertStatus(410);
    }
}
