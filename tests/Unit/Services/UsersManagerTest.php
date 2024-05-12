<?php

namespace Services;

use App\Services\ApiClient;
use App\Services\DatabaseClient;
use App\Services\TwitchProvider;
use App\Services\UsersDataManager;
use Mockery;
use Tests\TestCase;

class UsersManagerTest extends TestCase
{
    /**
     * @test
     */
    public function getUserByIdTest()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(ApiClient::class);
        $twitchProvider = $mockery->mock(TwitchProvider::class);
        $databaseClientMocker = $mockery->mock(DatabaseClient::class);
        $tokenExpected = 'u308tesk7yzmi8fe7el28e46dad3a5';
        $userExpected = json_encode(['data' => [[
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
            ->expects('getUserFromDatabase')
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
            ->andReturn($userExpected);
        $databaseClientMocker
            ->expects('insertUserInDatabase')
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

        $usersManager = new UsersDataManager($apiClient, $databaseClientMocker, $twitchProvider);
        $userByIdResult = $usersManager->userDataProvider('123456789');

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
