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
    private $databaseClient;
    private $topThreeProvider;
    private $videosProvider;
    private $currentDateTime;
    private $topsDataManager;
    private $mockery;
    protected function setUp(): void
    {
        $this->mockery = new Mockery();
        $this->databaseClient = $this->mockery->mock(DBClient::class);
        $this->topThreeProvider = $this->mockery->mock(TopThreeProvider::class);
        $this->videosProvider = $this->mockery->mock(VideosProvider::class);
        $this->currentDateTime = new DateTime();

        $this->topsDataManager = new TopsOfTheTopsDataManager(
            $this->databaseClient,
            $this->topThreeProvider,
            $this->videosProvider,
            $this->currentDateTime
        );
    }
    /**
     * @test
     */
    public function topsOfTheTopsDataProviderWithTopStreamers()
    {
        $time = 100;
        $topStreamers = true;
        $topThreeExpected = array('data' => [[
            'id' => '123456789',
            'name' => 'Football Manager',
        ]]);
        $streamersInTime = [['game_id' => '1234', 'user_name' => 'Eder']];
        $allIdsExpected = array(
            "123456",
            "789012",
            "345678",
        );
        $videoResponse = array([
            'user_name' => 'Eder',
            'total_views' => 100,
            'most_viewed_title' => '500 sobres del futchampios'
        ]);

        $this->databaseClient->shouldReceive('thereIsTopStreamers')
            ->andReturn($topStreamers);
        $this->topThreeProvider->shouldReceive('getTopThree')
            ->andReturn($topThreeExpected);
        $this->databaseClient->shouldReceive('getAllIds')
            ->andReturn($allIdsExpected);
        $this->databaseClient->shouldReceive('getInTimeStreamers')
            ->with($time)->andReturn($streamersInTime);
        $this->videosProvider->shouldReceive('getVideos')
            ->andReturn($videoResponse);
        $this->databaseClient->shouldReceive('insertStreamerInTops');

        $result = $this->topsDataManager->topsOfTheTopsDataProvider($time);

        $this->assertEquals($result, [$videoResponse]);
    }
    /**
     * @test
     */
    public function topsOfTheTopsDataProviderWithoutTopStreamers()
    {
        $time = 100;
        $topStreamers = false;
        $allIdsExpected = array(
            "123456",
            "789012",
            "345678",
        );
        $topThreeExpected = array('data' => [[
            'id' => '123456789',
            'name' => 'Football Manager',
        ]]);
        $videoResponse = array([
            'user_name' => 'Eder',
            'total_views' => 100,
            'most_viewed_title' => '500 sobres del futchampios'
        ]);

        $this->databaseClient->shouldReceive('thereIsTopStreamers')
            ->andReturn($topStreamers);
        $this->topThreeProvider->shouldReceive('getTopThree')
            ->andReturn($topThreeExpected);
        $this->databaseClient->shouldReceive('getAllIds')
            ->andReturn($allIdsExpected);
        $this->videosProvider->shouldReceive('getVideos')
            ->andReturn($videoResponse);
        $this->databaseClient->shouldReceive('insertStreamerInTops');

        $result = $this->topsDataManager->topsOfTheTopsDataProvider($time);

        $this->assertEquals([$videoResponse], $result);
    }
    /**
     * @test
     */
    public function findInTimeStreamerWhenExistStremersInTimeAndHaveSameGameId()
    {
        $gameIdExpected = '123456';
        $inTimeExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'Eder',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);
        $streamerExpected = $inTimeExpected[0];

        $result = $this->topsDataManager->findInTimeStreamer($gameIdExpected, $inTimeExpected);

        $this->assertEquals($result, $streamerExpected);
    }
    /**
     * @test
     */
    public function findInTimeStreamerWhenExistStremersInTimeAndNotHaveSameGameId()
    {
        $gameIdExpected = '1234567';
        $inTimeExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'Eder',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);

        $result = $this->topsDataManager->findInTimeStreamer($gameIdExpected, $inTimeExpected);

        $this->assertEquals($result, null);
    }
    /**
     * @test
     */
    public function findInTimeStreamerWhenNotExistStremersInTime()
    {
        $gameIdExpected = '1234567';
        $inTimeExpected = [];

        $result = $this->topsDataManager->findInTimeStreamer($gameIdExpected, $inTimeExpected);

        $this->assertEquals($result, null);
    }
    /**
     * @test
     */
    public function updateOrInsertStreamerUpdaters()
    {
        $gameNoVideosExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'Eder',
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

        $this->databaseClient->shouldReceive('updateStreamerInTops')
            ->once()
            ->with($gameNoVideosExpected, $dateExpected);
        $this->topsDataManager->updateOrInsertStreamer(
            $gameNoVideosExpected,
            $gameIdExpected,
            $allIdsExpected,
            $dateExpected
        );
    }
    /**
     * @test
     */
    public function updateOrInsertStreamerIserts()
    {
        $gameNoVideosExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'Eder',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);
        $gameIdExpected = '1234567';
        $allIdsExpected = array(
            "123456",
            "789012",
            "345678",
        );
        $dateExpected = date('Y-m-d H:i:s');

        $this->databaseClient->shouldReceive('insertStreamerInTops')
            ->once()
            ->with($gameNoVideosExpected, $dateExpected);
        $this->topsDataManager->updateOrInsertStreamer(
            $gameNoVideosExpected,
            $gameIdExpected,
            $allIdsExpected,
            $dateExpected
        );
    }
    /**
     * @test
     */
    public function processNoTopStreamersWithMultipleGames()
    {
        $gamesExpected = array([
            'id' => '123456789',
            'name' => 'Football Manager',
        ],
            [
                'id' => '7330',
                'name' => 'Valorant',
            ]);
        $dateExpected = date('Y-m-d H:i:s');
        $videosExpected1 = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'Eder',
            'total_videos' => 1,
            'total_views' => '7321'
        ]);
        $videosExpected2 = array([
            'game_id' => '7330',
            'game_name' => 'Valorant',
            'user_name' => 'Ivan',
            'total_videos' => 1,
            'total_views' => '23494'
        ]);

        $this->videosProvider->shouldReceive('getVideos')
            ->with($gamesExpected[0])
            ->andReturn($videosExpected1);
        $this->videosProvider->shouldReceive('getVideos')
            ->with($gamesExpected[1])
            ->andReturn($videosExpected2);

        $this->databaseClient->shouldReceive('insertStreamerInTops')
            ->with($videosExpected1, $dateExpected)
            ->once();
        $this->databaseClient->shouldReceive('insertStreamerInTops')
            ->with($videosExpected2, $dateExpected)
            ->once();

        $result = $this->topsDataManager->processNoTopStreamers($gamesExpected, $dateExpected);

        $this->assertEquals([$videosExpected1, $videosExpected2], $result);
    }
    /**
     * @test
     */
    public function processNoTopStreamersWithSingleGame()
    {
        $gamesExpected = array([
            'id' => '123456789',
            'name' => 'Football Manager',
        ]);
        $gameExpected = $gamesExpected[0];
        $dateExpected = date('Y-m-d H:i:s');
        $videosExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'Eder',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);

        $this->videosProvider->shouldReceive('getVideos')
            ->with($gameExpected)
            ->andReturn($videosExpected);
        $this->databaseClient->shouldReceive('insertStreamerInTops')
            ->with($videosExpected, $dateExpected)
            ->once();

        $result = $this->topsDataManager->processNoTopStreamers($gamesExpected, $dateExpected);

        $this->assertEquals($result, [$videosExpected]);
    }
    /**
     * @test
     */
    public function processNoTopStreamersWithEmptyGames()
    {
        $gamesExpected = [];
        $dateExpected = $this->currentDateTime->format('Y-m-d H:i:s');

        $result = $this->topsDataManager->processNoTopStreamers($gamesExpected, $dateExpected);

        $this->assertEquals([], $result);
    }
    /**
     * @test
     */
    public function processTopStreamersWithInTimeStreamer()
    {
        $gamesExpected = array([
            'id' => '7302',
            'name' => 'Valorant'
        ],[
            'id' => '1234',
            'name' => 'Fornite'
        ]);
        $InTimeExpected = array([
            'game_id' => '1234', 'user_name' => 'WillyRex'
        ]);
        $allIdsExpected = ['7302','1234'];
        $dateExpected = $this->currentDateTime->format('Y-m-d H:i:s');
        $videoExpected = array([
            'user_name' => 'Mixwell',
            'total_views' => 1679532,
            'most_viewed_title' => 'Consejos para no ser un Paco'
        ]);

        $this->videosProvider->shouldReceive('getVideos')
            ->with($gamesExpected[0])
            ->andReturn($videoExpected);

        $this->databaseClient->shouldReceive('updateStreamerInTops')
            ->once();

        $result = $this->topsDataManager->processTopStreamers(
            $gamesExpected,
            $InTimeExpected,
            $allIdsExpected,
            $dateExpected
        );

        $responseExpected = [
            $videoExpected,
            $InTimeExpected[0],
        ];
        $this->assertEquals($responseExpected, $result);
    }
    /**
     * @test
     */
    public function processTopStreamersWithoutInTimeStreamer()
    {
        $gamesExpected = array([
            'id' => '7302',
            'name' => 'Valorant'
        ]);
        $InTimeExpected = [];
        $allIdsExpected = ['7302'];
        $dateExpected = $this->currentDateTime->format('Y-m-d H:i:s');
        $videoExpected = array([
            'user_name' => 'Mixwell',
            'total_views' => 1679532,
            'most_viewed_title' => 'Consejos para no ser un Paco'
        ]);

        $this->videosProvider->shouldReceive('getVideos')
            ->with($gamesExpected[0])
            ->andReturn($videoExpected);

        $this->databaseClient->shouldReceive('updateStreamerInTops')
            ->once();

        $result = $this->topsDataManager->processTopStreamers(
            $gamesExpected,
            $InTimeExpected,
            $allIdsExpected,
            $dateExpected
        );

        $this->assertEquals([$videoExpected], $result);
    }
    /**
     * @test
     */
    public function processTopStreamersWithEmptyGames()
    {
        $gamesExpected = [];
        $InTimeExpected = [];
        $allIdsExpected = [];
        $dateExpected = $this->currentDateTime->format('Y-m-d H:i:s');

        $result = $this->topsDataManager->processTopStreamers(
            $gamesExpected,
            $InTimeExpected,
            $allIdsExpected,
            $dateExpected
        );

        $this->assertEquals([], $result);
    }
}
