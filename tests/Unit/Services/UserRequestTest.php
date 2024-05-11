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
        $usersRequest = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make([], $usersRequest->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
                // Simulamos envío con campo id vacío
        $this->assertArrayHasKey('id', $validator->errors()->messages());
        $this->assertEquals('The id field is required.', $validator->errors()->first('id'));
    }

    /** @test */
    public function idMustBeAnInteger()
    {
        $usersRequest = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

                // Simulamos envío con campo id='abc'
        $validator = $validatorFactory->make(['id' => 'abc'], $usersRequest->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('id', $validator->errors()->messages());
        $this->assertEquals('The id field must be a number.', $validator->errors()->first('id'));
    }

    /** @test */
    public function validIdPassesValidation()
    {
        $usersRequest = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['id' => '417603922'], $usersRequest->rules());

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }
}
