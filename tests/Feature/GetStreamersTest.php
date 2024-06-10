<?php

namespace Tests\Feature;

use App\Http\Infrastructure\Clients\APIClient;
use App\Http\Infrastructure\Clients\DBClient;
use App\Services\TwitchProvider;
use App\Services\StreamersDataManager;
use Mockery;
use Tests\TestCase;

class GetStreamersTest extends TestCase
{
    /**
     * @test
     */
    public function getStreamers()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(APIClient::class);
        $twitchProvider = $mockery->mock(TwitchProvider::class);
        $databaseClient = $mockery->mock(DBClient::class);
        $this->app
            ->when(StreamersDataManager::class)
            ->needs(APIClient::class)
            ->give(fn() => $apiClient);
        $this->app
            ->when(StreamersDataManager::class)
            ->needs(TwitchProvider::class)
            ->give(fn() => $twitchProvider);
        $this->app
            ->when(StreamersDataManager::class)
            ->needs(DBClient::class)
            ->give(fn() => $databaseClient);
        $tokenExpected = "u308tesk7yzmi8fe7el28e46dad3a5";
        $streamerExpected = json_encode(['data' => [[
            'id' => '123456789',
            'login' => 'login',
            'display_name' => 'display_name',
            'type' => '',
            'broadcaster_type' => '',
            'description' => 'description',
            'profile_image_url' => 'profile_image_url',
            'offline_image_url' => '',
            'view_count' => 0,
            'created_at' => '05-05-2024'
        ]]]);

        $databaseClient
            ->expects('getStreamerFromDatabase')
            ->with('123456789')
            ->once()
            ->andReturn(null);
        $twitchProvider
            ->expects('getToken')
            ->once()
            ->andReturn($tokenExpected);
        $apiClient
            ->expects('makeCurlCall')
            ->with(
                'https://api.twitch.tv/helix/users?id=123456789',
                [0 => 'Authorization: Bearer u308tesk7yzmi8fe7el28e46dad3a5']
            )
            ->once()
            ->andReturn($streamerExpected);
        $databaseClient
            ->expects('insertStreamerInDatabase')
            ->with(array('data' => [[
                'id' => '123456789',
                'login' => 'login',
                'display_name' => 'display_name',
                'type' => '',
                'broadcaster_type' => '',
                'description' => 'description',
                'profile_image_url' => 'profile_image_url',
                'offline_image_url' => '',
                'view_count' => 0,
                'created_at' => '05-05-2024'
            ]]))
            ->once();

        $streamerResponse = $this->get('/analytics/streamers?id=123456789');

        $streamerResponse->assertStatus(200);
        $checkStrPart1 = '[{"id":"123456789","login":"login","display_name":"display_name",';
        $checkStrPart2 = '"type":"","broadcaster_type":"","description":"description",';
        $checkStrPart3 = '"profile_image_url":"profile_image_url","offline_image_url":"",';
        $checkStrPart4 = '"view_count":0,"created_at":"05-05-2024"}]';
        $streamerResponse->assertContent($checkStrPart1 . $checkStrPart2 . $checkStrPart3 . $checkStrPart4);
    }
}
