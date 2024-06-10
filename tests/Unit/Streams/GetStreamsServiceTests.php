<?php

namespace Streams;

use App\Services\ApiClient;
use App\Services\GetStreamsService;
use App\Services\StreamsManager;
use Tests\TestCase;

class GetStreamsServiceTests extends TestCase
{
    /**
     * @test
     */
    public function executeDoesNotReturnEmptyArray()
    {
        $getStreamsService = new GetStreamsService(new StreamsManager(new ApiClient()));

        $arrayResponse = $getStreamsService->execute();

        $this->assertNotEmpty($arrayResponse);
    }

    /**
     * @test
     */
    public function executeDoesReturnProperUsernameTitleResponse()
    {
        $getStreamsService = new GetStreamsService(new StreamsManager(new ApiClient()));

        $arrayResponse = $getStreamsService->execute();

        foreach ($arrayResponse as $stream) {
            $this->assertArrayHasKey("user_name", $stream);
            $this->assertArrayHasKey("title", $stream);
        }
    }

    /**
 * @test
 */
    public function executeDoesNotReturnEmptyUsernameTitleResponse()
    {
        $getStreamsService = new GetStreamsService(new StreamsManager(new ApiClient()));

        $arrayResponse = $getStreamsService->execute();

        foreach ($arrayResponse as $stream) {
            $this->assertNotEmpty($stream["user_name"]);
            $this->assertNotEmpty($stream["title"]);
        }
    }
}
