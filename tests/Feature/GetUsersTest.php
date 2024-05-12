<?php

namespace Tests\Feature;

use App\Services\ApiClient;
use App\Services\DatabaseClient;
use App\Services\TwitchProvider;
use App\Services\UsersDataManager;
use Tests\TestCase;
use Mockery;

class GetUsersTest extends TestCase
{
    /**
     * @test
     */
    public function getUsers()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(ApiClient::class);
        $twitchProvider = $mockery->mock(TwitchProvider::class);
        $databaseClient = $mockery->mock(DatabaseClient::class);
        $this->app
            ->when(UsersDataManager::class)
            ->needs(ApiClient::class)
            ->give(fn() => $apiClient);
        $this->app
            ->when(UsersDataManager::class)
            ->needs(TwitchProvider::class)
            ->give(fn() => $twitchProvider);
        $this->app
            ->when(UsersDataManager::class)
            ->needs(DatabaseClient::class)
            ->give(fn() => $databaseClient);
        $tokenExpected = "u308tesk7yzmi8fe7el28e46dad3a5";
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

        $databaseClient
            ->expects('getUserFromDatabase')
            ->with('123456789')
            ->once()
            ->andReturn(null);
        $twitchProvider
            ->expects('getTokenTwitch')
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
        $databaseClient
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

        $userResponse = $this->get('/analytics/users?id=123456789');

        $userResponse->assertStatus(200);
        $checkStrPart1 = '[{"id":"123456789","login":"login","display_name":"display_name",';
        $checkStrPart2 = '"type":"","broadcaster_type":"","description":"description",';
        $checkStrPart3 = '"profile_image_url":"profile_image_url","offline_image_url":"",';
        $checkStrPart4 = '"view_count":0,"created_at":"05-05-2024"}]';
        $userResponse->assertContent($checkStrPart1 . $checkStrPart2 . $checkStrPart3 . $checkStrPart4);
    }
}
