<?php

namespace Services;

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\UnfollowDataManager;
use Exception;
use Mockery;
use Tests\TestCase;

class UnfollowDataManagerTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function testUnfollowDataProvider()
    {
        $mockery = new Mockery();
        $databaseClient = $mockery->mock(DBClient::class);
        $unfollowDataManager = new UnfollowDataManager($databaseClient);

        $user_id = 1;
        $streamer_id = '2';


        $databaseClient
            ->expects('userFollowsStreamer')
            ->with($user_id, $streamer_id)
            ->once()
            ->andReturn(true);

        $databaseClient
            ->expects('deleteFollow')
            ->with($user_id, $streamer_id)
            ->once();

        $result = $unfollowDataManager->unfollowDataProvider($user_id, $streamer_id);

        $this->assertEquals("Dejaste de seguir a : $streamer_id", $result);
    }
}
