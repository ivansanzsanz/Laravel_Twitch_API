<?php

namespace Requests;

use App\Http\Requests\StreamersRequest;
use Illuminate\Validation\Factory as ValidatorFactory;
use Tests\TestCase;

class StreamerRequestTest extends TestCase
{
    /** @test */

    public function idIsRequired()
    {
        $streamer_req = new StreamersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make([], $streamer_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->messages());
        $this->assertEquals('The id field is required.', $validator->errors()->first('id'));
    }

    /** @test */
    public function idMustBeAnInteger()
    {
        $streamer_req = new StreamersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['id' => 'abc'], $streamer_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->messages());
        $this->assertEquals('The id field must be an integer.', $validator->errors()->first('id'));
    }

    /** @test */
    public function validIdPassesValidation()
    {
        $streamer_req = new StreamersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['id' => '417603922'], $streamer_req->rules());

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }
}
