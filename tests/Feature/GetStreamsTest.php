<?php

namespace Tests\Feature;

use App\Http\Infrastructure\Clients\APIClient;
use App\Services\StreamsDataManager;
use App\Services\TwitchProvider;
use Mockery;
use Tests\TestCase;

class GetStreamsTest extends TestCase
{
    /**
     * @test
     */
    public function getStreams()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(APIClient::class);
        $twitchProvider = $mockery->mock(TwitchProvider::class);
        $this->app
            ->when(StreamsDataManager::class)
            ->needs(APIClient::class)
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
            ->expects('getToken')
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
