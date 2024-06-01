<?php

namespace Services;

use App\Services\GetStreamersService;
use App\Services\StreamersDataManager;
use Mockery;
use Tests\TestCase;

class GetStreamersServiceTest extends TestCase
{
    /**
     * @test
     */
    public function executeTest()
    {
        $mockery = new Mockery();
        $streamersDataManager = $mockery->mock(StreamersDataManager::class);
        $streamerExpected = array('data' => [[
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
        ]]);

        $streamersDataManager
            ->expects('streamersDataProvider')
            ->with('123456789')
            ->once()
            ->andReturn($streamerExpected);

        $getStreamersService = new GetStreamersService($streamersDataManager);
        $result = $getStreamersService->execute('123456789');

        $this->assertEquals($result, array(['id' => '123456789',
            'login' => 'login',
            'display_name' => 'display_name',
            'type' => '',
            'broadcaster_type' => '',
            'description' => 'description',
            'profile_image_url' => 'profile_image_url',
            'offline_image_url' => '',
            'view_count' => 0,
            'created_at' => '05-05-2024'
        ]));
    }
}
