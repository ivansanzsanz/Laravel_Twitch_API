<?php

namespace Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Services\StreamsDataManager;
use App\Services\TwitchProvider;
use Mockery;
use Tests\TestCase;

class StreamsDataManagerTests extends TestCase
{
    /**
     * @test
     */
    public function streamsDataProviderTest()
    {
        $mockery = new Mockery();
        $apiClient = $mockery->mock(APIClient::class);
        $twitchProvider = $mockery->mock(TwitchProvider::class);
        $tokenExpected = 'u308tesk7yzmi8fe7el28e46dad3a5';
        $streamExpected = json_encode(['data' => [[
            "title" => "title",
            "user_name" => "user_name"
        ]]]);

        $twitchProvider
            ->expects('getToken')
            ->once()
            ->andReturn($tokenExpected);
        $apiClient
            ->expects('makeCurlCall')
            ->with(
                'https://api.twitch.tv/helix/streams',
                [0 => 'Authorization: Bearer u308tesk7yzmi8fe7el28e46dad3a5']
            )
            ->once()
            ->andReturn($streamExpected);

        $streamsManager = new StreamsDataManager($apiClient, $twitchProvider);
        $streamsResult = $streamsManager->streamsDataProvider();

        $this->assertEquals($streamsResult, array('data' => [[
            "title" => "title",
            "user_name" => "user_name"
        ]]));
    }
}
