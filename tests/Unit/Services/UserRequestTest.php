<?php

namespace Tests\Unit\Services;

//use PHPUnit\Framework\TestCase;
use App\Http\Requests\UsersRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Illuminate\Validation\Factory as ValidatorFactory;
use App\Models\User;

//use Illuminate\Validation\ValidationException;

class UserRequestTest extends TestCase
{
    /** @test */

    public function idIsRequired()
    {
        $user_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make([], $user_req->rules()); // Simulamos envío sin ID

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->messages());
        $this->assertEquals('The id field is required.', $validator->errors()->first('id'));
    }

    /** @test */
    public function idMustBeAnInteger()
    {
        $user_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['id' => 'abc'], $user_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->messages());
        $this->assertEquals('The id field must be a number.', $validator->errors()->first('id'));
    }

    /** @test */
    public function validIdPassesValidation()
    {
        $user_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['id' => '417603922'], $user_req->rules());

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }
}
