<?php

namespace Requests;

use App\Http\Requests\UnfollowRequest;
use Illuminate\Validation\Factory as ValidatorFactory;
use Tests\TestCase;

class UnfollowRequestTest extends TestCase
{
    /** @test */
    public function userIdIsRequired()
    {
        $follow_req = new UnfollowRequest();

        $validatorFactory = app(ValidatorFactory::class);
        $validator = $validatorFactory->make([], $follow_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->messages());
        $this->assertEquals('The user id field is required.', $validator->errors()->first('user_id'));
    }

    /** @test */
    public function streamerIdIsRequired()
    {
        $follow_req = new UnfollowRequest();

        $validatorFactory = app(ValidatorFactory::class);
        $validator = $validatorFactory->make([], $follow_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('streamer_id', $validator->errors()->messages());
        $this->assertEquals('The streamer id field is required.', $validator->errors()->first('streamer_id'));
    }

    /** @test */
    public function userIdMustBeAnInteger()
    {
        $follow_req = new UnfollowRequest();

        $validatorFactory = app(ValidatorFactory::class);
        $validator = $validatorFactory->make(['user_id' => 'ivanig'], $follow_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->messages());
        $this->assertEquals('The user id field must be an integer.', $validator->errors()->first('user_id'));
    }

    /** @test */
    public function streamerIdMustBeAString()
    {
        $follow_req = new UnfollowRequest();

        $validatorFactory = app(ValidatorFactory::class);
        $validator = $validatorFactory->make(['streamer_id' => 12345], $follow_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('streamer_id', $validator->errors()->messages());
        $this->assertEquals('The streamer id field must be a string.', $validator->errors()->first('streamer_id'));
    }

    /** @test */
    public function validUserIDAndStreamerIDPassesValidation()
    {
        $follow_req = new UnfollowRequest();

        $validatorFactory = app(ValidatorFactory::class);
        $validator = $validatorFactory->make(['user_id' => 12345, 'streamer_id' => '1234'], $follow_req->rules());

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }
}
