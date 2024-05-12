<?php

namespace Tests\Feature;

use App\Services\ApiClient;
use App\Services\StreamsManager;
use Tests\TestCase;
use Mockery;

class GetStreamsTest extends TestCase
{
    /**
     * @test
     */
    public function getStreams()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(ApiClient::class);
        $this->app
            ->when(StreamsManager::class)
            ->needs(ApiClient::class)
            ->give(fn() => $apiClient);
        $tokenExpected = json_encode([
            'access_token' => 'ivanigg',
            'expires_in' => 5175216,
            'token_type' => 'bearer',
        ]);
        $streamsExpected = json_encode(['data' => [[
            'title' => 'Stream title',
            'user_name' => 'user_name',
        ]]]);

        $apiClient
            ->expects('getToken')
            ->with('https://id.twitch.tv/oauth2/token')
            ->once()
            ->andReturn($tokenExpected);
        $apiClient
            ->expects('makeCurlCall')
            ->with('https://api.twitch.tv/helix/streams', [0 => 'Authorization: Bearer ivanigg'])
            ->once()
            ->andReturn($streamsExpected);

        $response = $this->get('/analytics/streams');

        $response->assertStatus(200);
        $response->assertContent('[{"user_name":"user_name","title":"Stream title"}]');
    }
}
