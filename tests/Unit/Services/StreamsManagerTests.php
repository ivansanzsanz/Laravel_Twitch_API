<?php

namespace Services;

use App\Services\ApiClient;
use App\Services\StreamsDataManager;
use Tests\TestCase;

class StreamsManagerTests extends TestCase
{
    /**
     * @test
     */
    public function getStreamsDoesNotReturnError()
    {
        $streamsManager = new StreamsDataManager(new ApiClient());

        $streamsResponse = $streamsManager->streamsDataProvider();

        $this->assertNotEquals("Error en la peticion curl de los streams", $streamsResponse);
    }

    /**
     * @test
     */
    public function getStreamsDoesNotReturnEmptyData()
    {
        $streamsManager = new StreamsDataManager(new ApiClient());

        $streamsResponse = $streamsManager->streamsDataProvider();

        $this->assertNotEmpty($streamsResponse["data"]);
    }
}
