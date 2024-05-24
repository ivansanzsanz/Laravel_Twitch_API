<?php

namespace Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Http\Infrastructure\Clients\DBClient;
use App\Services\TwitchProvider;
use App\Services\StreamersDataManager;
use Mockery;
use Tests\TestCase;

class StreamersDataManagerTest extends TestCase
{
    /**
     * @test
     */
    public function streamerDataProviderTest()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(APIClient::class);
        $twitchProvider = $mockery->mock(TwitchProvider::class);
        $databaseClientMocker = $mockery->mock(DBClient::class);
        $tokenExpected = 'u308tesk7yzmi8fe7el28e46dad3a5';
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

        $databaseClientMocker
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
        $databaseClientMocker
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

        $streamersManager = new StreamersDataManager($apiClient, $databaseClientMocker, $twitchProvider);
        $userByIdResult = $streamersManager->streamersDataProvider('123456789');

        $this->assertEquals($userByIdResult, array('data' => [[
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
        ]]));
    }
}
