<?php

namespace Tests\Feature;

use App\Services\ApiClient;
use App\Services\DatabaseClient;
use App\Services\UsersManager;
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
        $databaseClient = $mockery->mock(DatabaseClient::class);
        $this->app
            ->when(UsersManager::class)
            ->needs(ApiClient::class)
            ->give(fn() => $apiClient);
        $this->app
            ->when(UsersManager::class)
            ->needs(DatabaseClient::class)
            ->give(fn() => $databaseClient);
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

        $databaseClient
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

        $response = $this->get('/analytics/users?id=123456789');
        //$response = $this->get('/analytics/users?id=417603922');

        $response->assertStatus(200);
        $checkStr1 = '[{"id":"123456789","login":"login","display_name":"display_name",';
        $checkStr2 = '"type":"","broadcaster_type":"","description":"description",';
        $checkStr3 = '"profile_image_url":"profile_image_url","offline_image_url":"",';
        $checkStr4 = '"view_count":0,"created_at":"05-05-2024"}]';
        $response->assertContent($checkStr1 . $checkStr2 . $checkStr3 . $checkStr4);
    }
}
