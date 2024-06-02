<?php

namespace Services;

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\FollowDataManager;
use Exception;
use Mockery;
use Tests\TestCase;

class FollowDataManagerTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function testFollowDataProvider()
    {
        $mockery = new Mockery();
        $databaseClient = $mockery->mock(DBClient::class);
        $followsDataManager = new FollowDataManager($databaseClient);

        $user_id = 1;
        $streamer_id = '2';


        $databaseClient
            ->expects('userFollowsStreamer')
            ->with($user_id, $streamer_id)
            ->once()
            ->andReturn(false);

        $databaseClient
            ->expects('insertFollow')
            ->with($user_id, $streamer_id)
            ->once();

        $result = $followsDataManager->followDataProvider($user_id, $streamer_id);

        $this->assertEquals("Ahora sigues a : $streamer_id", $result);
    }
}
