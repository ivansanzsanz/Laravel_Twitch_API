<?php

namespace Tests\Feature;

use App\Services\ApiClient;
use App\Services\StreamsDataManager;
use App\Services\TwitchProvider;
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
        $twitchProvider = $mockery->mock(TwitchProvider::class);
        $this->app
            ->when(StreamsDataManager::class)
            ->needs(ApiClient::class)
            ->give(fn() => $apiClient);
        $this->app
            ->when(StreamsDataManager::class)
            ->needs(TwitchProvider::class)
            ->give(fn() => $twitchProvider);
        $tokenExpected = 'u308tesk7yzmi8fe7el28e46dad3a5';
        $streamsExpected = json_encode(['data' => [[
            'title' => 'Stream title',
            'user_name' => 'user_name',
        ]]]);

        $twitchProvider
            ->expects('getTokenTwitch')
            ->once()
            ->andReturn($tokenExpected);
        $apiClient
            ->expects('makeCurlCall')
            ->with('https://api.twitch.tv/helix/streams', [0 => 'Authorization: Bearer u308tesk7yzmi8fe7el28e46dad3a5'])
            ->once()
            ->andReturn($streamsExpected);

        $response = $this->get('/analytics/streams');

        $response->assertStatus(200);
        $response->assertContent('[{"user_name":"user_name","title":"Stream title"}]');
    }
}
