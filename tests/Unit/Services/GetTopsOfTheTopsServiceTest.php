<?php

namespace Services;

use App\Services\GetStreamersService;
use App\Services\GetTopsOfTheTopsService;
use App\Services\StreamersDataManager;
use App\Services\TopsOfTheTopsDataManager;
use Mockery;
use Tests\TestCase;

class GetTopsOfTheTopsServiceTest extends TestCase
{
    /**
     * @test
     */
    public function executeTest()
    {
        $mockery = new Mockery();
        $topsDataManager = $mockery->mock(TopsOfTheTopsDataManager::class);
        $topsExpected = array([
            'game_id' => '123456',
            'game_name' => 'Football Manager',
            'user_name' => 'User',
            'total_videos' => 1,
            'total_views' => '1000000'
        ]);

        $topsDataManager
            ->expects('topsOfTheTopsDataProvider')
            ->with(100)
            ->once()
            ->andReturn($topsExpected);

        $getTopsService = new GetTopsOfTheTopsService($topsDataManager);
        $result = $getTopsService->execute(100);

        $this->assertEquals($result, $topsExpected);
    }
}
