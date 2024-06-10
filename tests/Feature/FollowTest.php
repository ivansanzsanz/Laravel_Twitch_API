<?php

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\FollowDataManager;
use Tests\TestCase;
use Mockery;

class FollowTest extends TestCase
{
    /**
     * @test
     */

    public function followStreamer()
    {
        $mockery = new Mockery();
        $databaseClient = $mockery->mock(DBClient::class);
        $this->app
            ->when(FollowDataManager::class)
            ->needs(DBClient::class)
            ->give(fn() => $databaseClient);

        $user_id = 1;
        $streamer_id = '1234';

        $followData = array(
            'user_id' => $user_id,
            'streamer_id' => $streamer_id
        );

        $resultExpected = '{"message":"Ahora sigues a : ' . $streamer_id . '"}';

        $databaseClient
            ->expects('userFollowsStreamer')
            ->with($user_id, $streamer_id)
            ->once()
            ->andReturn(false);
        $databaseClient
            ->expects('insertFollow')
            ->with($user_id, $streamer_id)
            ->once();

        $followResponse = $this->post('/analytics/follow', $followData);

        $followResponse->assertStatus(201);
        $followResponse->assertContent($resultExpected);
    }
}
