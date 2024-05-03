<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function returns_streams()
    {
        /*
        $curlExecutor = Mockery::mock(CurlExecutor::class);

        $expectedResponse = [
            'access_token' => 'qsdgfrhtgefrqfhgrtjy',
            'expires_in' => '12345643',
            'token_type' => 'bearer',
        ];

        $expectedApiResponse = [
            'title' => 'Stream title',
            'user_name' => 'user_name',
        ];

        $this->app
            ->when(NewTwitchApi::class)
            ->needs(CurlExecutor::class)
            ->give(fn() => $curlExecutor)

        $curlExecutor
            ->expects('getToken')
            ->with('https://id.twitch.tv/oauth/token')
            ->once()
            ->andReturn($expectedResponse);

        $curlExecutor
            ->expects('makeCurlCall')
            ->with('https://id.twitch.tv/oauth/token')
            ->once()
            ->andReturn($expectedApiResponse);
        */

        $response = $this->get('/analytics/streams');

        $response->assertStatus(200);
        $response->assertContent('{
          "user_name": "Stream title",
          "title": "user_name"
        }');
    }
}
