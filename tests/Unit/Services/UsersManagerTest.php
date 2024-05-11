<?php

namespace Services;

use App\Services\ApiClient;
use App\Services\DatabaseClient;
use App\Services\UsersManager;
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
        $databaseClientMocker = $mockery->mock(DatabaseClient::class);
        $tokenExpected = json_encode([
            'access_token' => 'u308tesk7yzmi8fe7el28e46dad3a5',
            'expires_in' => 5175216,
            'token_type' => 'bearer',
        ]);
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
        $apiClient
            ->expects('getToken')
            ->with('https://id.twitch.tv/oauth2/token')
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

        $usersManager = new UsersManager($apiClient, $databaseClientMocker);
        $userByIdResult = $usersManager->getUserById('123456789');

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

    /**
     * @test
     */
    public function getTokenTwitchTest()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(ApiClient::class);
        $databaseClientMocker = $mockery->mock(DatabaseClient::class);
        $tokenExpected = json_encode([
            'access_token' => 'u308tesk7yzmi8fe7el28e46dad3a5',
            'expires_in' => 5175216,
            'token_type' => 'bearer',
        ]);

        $apiClient
            ->expects('getToken')
            ->with('https://id.twitch.tv/oauth2/token')
            ->once()
            ->andReturn($tokenExpected);

        $usersManager = new UsersManager($apiClient, $databaseClientMocker);
        $tokenTwitchResult = $usersManager->getTokenTwitch();

        $this->assertEquals($tokenTwitchResult, 'u308tesk7yzmi8fe7el28e46dad3a5');
    }
}
