<?php

namespace Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Http\Infrastructure\Clients\DBClient;
use App\Services\TwitchProvider;
use Mockery;
use Tests\TestCase;

class TwitchProviderTest extends TestCase
{
    /**
     * @test
     */
    public function getTokenTwitchWhenThereIsNoTokenInDatabase()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(APIClient::class);
        $databaseClient = $mockery->mock(DBClient::class);
        $tokenExpected = json_encode([
            'access_token' => 'u308tesk7yzmi8fe7el28e46dad3a5',
            'expires_in' => 5175216,
            'token_type' => 'bearer',
        ]);

        $databaseClient
            ->expects('thereIsATokenInTheDB')
            ->once()
            ->andReturn(false);
        $apiClient
            ->expects('getTokenTwitch')
            ->with('https://id.twitch.tv/oauth2/token')
            ->once()
            ->andReturn($tokenExpected);
        $databaseClient
            ->expects('insertTokenInDatabase')
            ->with('u308tesk7yzmi8fe7el28e46dad3a5')
            ->once();

        $twitchProvider = new TwitchProvider($apiClient, $databaseClient);
        $tokenTwitchResult = $twitchProvider->getToken();

        $this->assertEquals($tokenTwitchResult, 'u308tesk7yzmi8fe7el28e46dad3a5');
    }

    /**
     * @test
     */
    public function getTokenTwitchWhenThereIsATokenInDatabase()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(APIClient::class);
        $databaseClient = $mockery->mock(DBClient::class);
        $tokenExpected = 'u308tesk7yzmi8fe7el28e46dad3a5';

        $databaseClient
            ->expects('thereIsATokenInTheDB')
            ->once()
            ->andReturn(true);
        $databaseClient
            ->expects('getTokenFromDatabase')
            ->once()
            ->andReturn($tokenExpected);

        $twitchProvider = new TwitchProvider($apiClient, $databaseClient);
        $tokenTwitchResult = $twitchProvider->getToken();

        $this->assertEquals($tokenTwitchResult, 'u308tesk7yzmi8fe7el28e46dad3a5');
    }
}
