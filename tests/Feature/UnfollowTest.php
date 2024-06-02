<?php

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\UnfollowDataManager;
use Tests\TestCase;
use Mockery;

class UnfollowTest extends TestCase
{
    /**
     * @test
     */

    public function unfollowStreamer()
    {
        $mockery = new Mockery();
        $databaseClient = $mockery->mock(DBClient::class);
        $this->app
            ->when(UnfollowDataManager::class)
            ->needs(DBClient::class)
            ->give(fn() => $databaseClient);

        $user_id = 1;
        $streamer_id = '1234';

        $followData = array(
            'user_id' => $user_id,
            'streamer_id' => $streamer_id
        );

        $resultExpected = '{"message":"Dejaste de seguir a : ' . $streamer_id . '"}';

        $databaseClient
            ->expects('userFollowsStreamer')
            ->with($user_id, $streamer_id)
            ->once()
            ->andReturn(true);
        $databaseClient
            ->expects('deleteFollow')
            ->with($user_id, $streamer_id)
            ->once();

        $followResponse = $this->delete('/analytics/unfollow', $followData);

        $followResponse->assertStatus(201);
        $followResponse->assertContent($resultExpected);
    }
}
