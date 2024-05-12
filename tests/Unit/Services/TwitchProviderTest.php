<?php

namespace Services;

use App\Services\ApiClient;
use App\Services\DatabaseClient;
use App\Services\TwitchProvider;
use Mockery;
use Tests\TestCase;

class TwitchProviderTest extends TestCase
{
    /**
     * @test
     */
    public function getTokenTwitchTest()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(ApiClient::class);
        $databaseClient = $mockery->mock(DatabaseClient::class);
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
            ->expects('getToken')
            ->with('https://id.twitch.tv/oauth2/token')
            ->once()
            ->andReturn($tokenExpected);
        $databaseClient
            ->expects('insertTokenInDatabase')
            ->with('u308tesk7yzmi8fe7el28e46dad3a5')
            ->once();

        $twitchProvider = new TwitchProvider($apiClient, $databaseClient);
        $tokenTwitchResult = $twitchProvider->getTokenTwitch();

        $this->assertEquals($tokenTwitchResult, 'u308tesk7yzmi8fe7el28e46dad3a5');
    }
}
