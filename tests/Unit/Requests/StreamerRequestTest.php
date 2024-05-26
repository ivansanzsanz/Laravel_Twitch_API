<?php

namespace Requests;

use App\Http\Requests\StreamersRequest;
use App\Http\Requests\TopsOfTheTopsRequest;
use Illuminate\Validation\Factory as ValidatorFactory;
use Tests\TestCase;

class StreamerRequestTest extends TestCase
{
    /** @test */
    public function sinceMustBeAnInteger()
    {
        $topsOfTheTopsRequest = new TopsOfTheTopsRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['since' => 'abc'], $topsOfTheTopsRequest->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('since', $validator->errors()->messages());
        $this->assertEquals('The since field must be an integer.', $validator->errors()->first('since'));
    }

    /** @test */
    public function validIdPassesValidation()
    {
        $topsOfTheTopsRequest = new TopsOfTheTopsRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['since' => 100], $topsOfTheTopsRequest->rules());

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }
}
