<?php

namespace Streams;

use App\Services\ApiClient;
use App\Services\StreamsManager;
use Tests\TestCase;

class StreamsManagerTests extends TestCase
{
    /**
     * @test
     */
    public function getStreamsDoesNotReturnError()
    {
        $streamsManager = new StreamsManager(new ApiClient());

        $streamsResponse = $streamsManager->getStreams();

        $this->assertNotEquals("Error en la peticion curl de los streams", $streamsResponse);
    }

    /**
     * @test
     */
    public function getStreamsDoesNotReturnEmptyData()
    {
        $streamsManager = new StreamsManager(new ApiClient());

        $streamsResponse = $streamsManager->getStreams();

        $this->assertNotEmpty($streamsResponse["data"]);
    }
}
