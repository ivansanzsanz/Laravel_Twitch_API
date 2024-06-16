<?php

namespace Services;

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\TopsOfTheTopsDataManager;
use App\Services\TopThreeProvider;
use App\Services\VideosProvider;
use DateTime;
use Mockery;
use Tests\TestCase;

class TopsOfTheTopsDataManagerTest extends TestCase
{
    /**
     * @test
     */
    public function topsOfTheTopsDataProvider()
    {
        $mockery = new Mockery();
        $dbClient = $mockery->mock(DBClient::class);
        $topThreeProvider = $mockery->mock(TopThreeProvider::class);
        $videosProvider = $mockery->mock(VideosProvider::class);
        $currentDateTime = new DateTime("2024-05-26 10:55:51");
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
        $topsExpected = array([
            "game_id" => "123456",
            "game_name" => "Football Manager",
            "user_name" => "User",
            "total_videos" => 1,
            "total_views" => "1000000"
        ]);

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
        $dbClient
            ->expects('insertStreamerInTops')
            ->with($videosExpected, $dateExpected)
            ->once();

        $topsDataManager = new TopsOfTheTopsDataManager(
            $dbClient,
            $topThreeProvider,
            $videosProvider,
            $currentDateTime
        );
        $result = $topsDataManager->topsOfTheTopsDataProvider(100);

        $this->assertEquals($result, $topsExpected);
    }

    /**
     * @test
     */
    public function processTopStreamers()
    {
        $mockery = new Mockery();
        $dbClient = $mockery->mock(DBClient::class);
        $topThreeProvider = $mockery->mock(TopThreeProvider::class);
        $videosProvider = $mockery->mock(VideosProvider::class);
        $currentDateTime = new DateTime("2024-05-26 10:55:51");
        $gamesExpected = array([
            'id' => '123456789',
            'name' => 'Football Manager',
        ]);
        $inTimeExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'User',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);
        $allIdsExpected = array(
            "123456",
            "789012",
            "345678",
        );
        $dateExpected = date('Y-m-d H:i:s');
        $videosExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'User',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);
        $topsExpected = array([[
            "game_id" => "123456",
            "game_name" => "Football Manager",
            "user_name" => "User",
            "total_videos" => 1,
            "total_views" => "1000000"
        ]]);

        $videosProvider
            ->expects('getVideos')
            ->with($gamesExpected[0])
            ->once()
            ->andReturn($videosExpected);
        $dbClient
            ->expects('insertStreamerInTops')
            ->with($videosExpected, $dateExpected)
            ->once();

        $topsDataManager = new TopsOfTheTopsDataManager(
            $dbClient,
            $topThreeProvider,
            $videosProvider,
            $currentDateTime
        );
        $result = $topsDataManager->processTopStreamers(
            $gamesExpected,
            $inTimeExpected,
            $allIdsExpected,
            $dateExpected
        );

        $this->assertEquals($result, $topsExpected);
    }

    /**
     * @test
     */
    public function processNoTopStreamers()
    {
        $mockery = new Mockery();
        $dbClient = $mockery->mock(DBClient::class);
        $topThreeProvider = $mockery->mock(TopThreeProvider::class);
        $videosProvider = $mockery->mock(VideosProvider::class);
        $currentDateTime = new DateTime("2024-05-26 10:55:51");
        $gamesExpected = array([
            'id' => '123456789',
            'name' => 'Football Manager',
        ]);
        $gameExpected = $gamesExpected[0];
        $dateExpected = date('Y-m-d H:i:s');
        $videosExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'User',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);
        $topsExpected = array([[
            "game_id" => "123456",
            "game_name" => "Football Manager",
            "user_name" => "User",
            "total_videos" => 1,
            "total_views" => "1000000"
        ]]);

        $videosProvider
            ->expects('getVideos')
            ->with($gameExpected)
            ->once()
            ->andReturn($videosExpected);
        $dbClient
            ->expects('insertStreamerInTops')
            ->with($videosExpected, $dateExpected)
            ->once();

        $topsDataManager = new TopsOfTheTopsDataManager(
            $dbClient,
            $topThreeProvider,
            $videosProvider,
            $currentDateTime
        );
        $result = $topsDataManager->processNoTopStreamers($gamesExpected, $dateExpected);

        $this->assertEquals($result, $topsExpected);
    }

    /**
     * @test
     */
    public function updateOrInsertStreamer()
    {
        $mockery = new Mockery();
        $dbClient = $mockery->mock(DBClient::class);
        $topThreeProvider = $mockery->mock(TopThreeProvider::class);
        $videosProvider = $mockery->mock(VideosProvider::class);
        $currentDateTime = new DateTime("2024-05-26 10:55:51");
        $gameNoVideosExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'User',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);
        $gameIdExpected = '123456';
        $allIdsExpected = array(
            "123456",
            "789012",
            "345678",
        );
        $dateExpected = date('Y-m-d H:i:s');

        $dbClient
            ->expects('updateStreamerInTops')
            ->with($gameNoVideosExpected, $dateExpected)
            ->once();

        $topsDataManager = new TopsOfTheTopsDataManager(
            $dbClient,
            $topThreeProvider,
            $videosProvider,
            $currentDateTime
        );
        $topsDataManager->updateOrInsertStreamer(
            $gameNoVideosExpected,
            $gameIdExpected,
            $allIdsExpected,
            $dateExpected
        );
    }

    /**
     * @test
     */
    public function findInTimeStreamer()
    {
        $mockery = new Mockery();
        $dbClient = $mockery->mock(DBClient::class);
        $topThreeProvider = $mockery->mock(TopThreeProvider::class);
        $videosProvider = $mockery->mock(VideosProvider::class);
        $currentDateTime = new DateTime("2024-05-26 10:55:51");
        $gameIdExpected = '123456';
        $inTimeExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'User',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);
        $streamerExpected = $inTimeExpected[0];

        $topsDataManager = new TopsOfTheTopsDataManager(
            $dbClient,
            $topThreeProvider,
            $videosProvider,
            $currentDateTime
        );
        $result = $topsDataManager->findInTimeStreamer($gameIdExpected, $inTimeExpected);

        $this->assertEquals($result, $streamerExpected);
    }
}
