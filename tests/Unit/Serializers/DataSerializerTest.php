<?php

namespace Serializers;

use App\Serializers\DataSerializer;
use Tests\TestCase;

class DataSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function givenDataReturnsJsonEncodedData()
    {
        $dataSerializer = new DataSerializer();

        $response = $dataSerializer->serializeData("data", 200);

        $this->assertEquals($response, response()->json("data", 200));
    }
}
