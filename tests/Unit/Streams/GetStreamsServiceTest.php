<?php

namespace Streams;

use App\Services\GetStreamsService;
use App\Services\StreamsManager;
use Mockery;
use Tests\TestCase;

class GetStreamsServiceTest extends TestCase
{
    /**
     * @test
     */
    public function executeStreamsValidResponseTest()
    {
        $mockery = new Mockery();
        $streamsManager = $mockery->mock(StreamsManager::class);
        $SMResponseExpected = array('data' => [
            [
                "title" => "titulo1",
                "random" => "random",
                "user_name" => "user_name1"
            ],
            [
                "title" => "titulo2",
                "random" => "random",
                "user_name" => "user_name2"
            ],
            [
                "title" => "titulo3",
                "random" => "random",
                "user_name" => "user_name3"
            ]
        ]);

        $streamsManager
            ->expects('getStreams')
            ->with()
            ->once()
            ->andReturn($SMResponseExpected);

        $getStreamsService = new GetStreamsService($streamsManager);
        $responseWithMockery = $getStreamsService->execute();

        $this->assertEquals($responseWithMockery, array(
            [
                "user_name" => "user_name1",
                "title" => "titulo1"
            ],
            [
                "user_name" => "user_name2",
                "title" => "titulo2"
            ],
            [
                "user_name" => "user_name3",
                "title" => "titulo3"
            ]
        ));
    }
}
