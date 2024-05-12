<?php

namespace Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Services\StreamsDataManager;
use Tests\TestCase;

class StreamsManagerTests extends TestCase
{
    /**
     * @test
     */
    public function getStreamsDoesNotReturnError()
    {
        $streamsManager = new StreamsDataManager(new APIClient());

        $streamsResponse = $streamsManager->streamsDataProvider();

        $this->assertNotEquals("Error en la peticion curl de los streams", $streamsResponse);
    }

    /**
     * @test
     */
    public function getStreamsDoesNotReturnEmptyData()
    {
        $streamsManager = new StreamsDataManager(new APIClient());

        $streamsResponse = $streamsManager->streamsDataProvider();

        $this->assertNotEmpty($streamsResponse["data"]);
    }
}
