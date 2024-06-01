<?php

namespace Tests\Feature;

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\TopsOfTheTopsDataManager;
use App\Services\TopThreeProvider;
use App\Services\VideosProvider;
use DateTime;
use Mockery;
use Tests\TestCase;

class GetTopsOfTheTopsTest extends TestCase
{
    /**
     * @test
     */
    public function getTopsOfTheTops()
    {
        $mockery = new Mockery();
        $dbClient = $mockery->mock(DBClient::class);
        $topThreeProvider = $mockery->mock(TopThreeProvider::class);
        $videosProvider = $mockery->mock(VideosProvider::class);
        $currentDateTime = new DateTime("2024-05-26 10:55:51");
        $this->app
            ->when(TopsOfTheTopsDataManager::class)
            ->needs(DBClient::class)
            ->give(fn() => $dbClient);
        $this->app
            ->when(TopsOfTheTopsDataManager::class)
            ->needs(TopThreeProvider::class)
            ->give(fn() => $topThreeProvider);
        $this->app
            ->when(TopsOfTheTopsDataManager::class)
            ->needs(VideosProvider::class)
            ->give(fn() => $videosProvider);
        $this->app
            ->when(TopsOfTheTopsDataManager::class)
            ->needs(DateTime::class)
            ->give(fn() => $currentDateTime);

        $topThreeExpected = array('data' => [[
            'id' => '123456789',
            'name' => 'Football Manager',
        ]]);
        $gameExpected = $topThreeExpected['data'][0];
        $allIdsExpected = array(
            "123456",
            "789012",
            "345678",
        );
        $videosExpected = array(
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'User',
            'total_videos' => 1,
            'total_views' => '1000000'
        );
        $dateExpected = $currentDateTime->format('Y-m-d H:i:s');

        $dbClient
            ->expects('thereIsTopStreamers')
            ->once()
            ->andReturn(false);
        $topThreeProvider
            ->expects('getTopThree')
            ->once()
            ->andReturn($topThreeExpected);
        $dbClient
            ->expects('getAllIds')
            ->once()
            ->andReturn($allIdsExpected);
        $videosProvider
            ->expects('getVideos')
            ->with($gameExpected)
            ->once()
            ->andReturn($videosExpected);
        /*$dbClient
            ->shouldReceive('insertStreamerInTops')
            ->withArgs(function ($arg1, $arg2) use ($videosExpected, $dateExpected) {
                dd($arg1, $arg2); // Dump and die to see the actual arguments
                return $arg1 === $videosExpected && $arg2 === $dateExpected;
            });*/
        $dbClient
            ->expects('insertStreamerInTops')
            ->with($videosExpected, $dateExpected)
            ->once();

        $response = $this->get('/analytics/topsofthetops');

        //dd($response);

        $response->assertStatus(200);

        $topsExpected1 = '[{"game_id":"123456","game_name":"Football Manager","user_name":"User"';
        $topsExpected2 = ',"total_videos":1,"total_views":"1000000"}]';
        $response->assertContent($topsExpected1 . $topsExpected2);
    }
}
