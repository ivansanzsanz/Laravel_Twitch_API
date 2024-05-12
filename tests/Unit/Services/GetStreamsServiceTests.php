<?php

namespace Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Services\GetStreamsService;
use App\Services\StreamsDataManager;
use Tests\TestCase;

class GetStreamsServiceTests extends TestCase
{
    /**
     * @test
     */
    public function executeDoesNotReturnEmptyArray()
    {
        $getStreamsService = new GetStreamsService(new StreamsDataManager(new APIClient()));

        $streamsResponse = $getStreamsService->execute();

        $this->assertNotEmpty($streamsResponse);
    }

    /**
     * @test
     */
    public function executeDoesReturnProperUsernameTitleResponse()
    {
        $getStreamsService = new GetStreamsService(new StreamsDataManager(new APIClient()));

        $streamsResponse = $getStreamsService->execute();

        foreach ($streamsResponse as $stream) {
            $this->assertArrayHasKey("user_name", $stream);
            $this->assertArrayHasKey("title", $stream);
        }
    }

    /**
 * @test
 */
    public function executeDoesNotReturnEmptyUsernameTitleResponse()
    {
        $getStreamsService = new GetStreamsService(new StreamsDataManager(new APIClient()));

        $streamsResponse = $getStreamsService->execute();

        foreach ($streamsResponse as $stream) {
            $this->assertNotEmpty($stream["user_name"]);
            $this->assertNotEmpty($stream["title"]);
        }
    }
}
