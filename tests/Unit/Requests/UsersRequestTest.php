<?php

namespace Requests;

use App\Http\Requests\UsersRequest;
use Illuminate\Validation\Factory as ValidatorFactory;
use Tests\TestCase;

class UsersRequestTest extends TestCase
{
    /** @test */
    public function usernameIsRequired()
    {
        $users_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make([], $users_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('username', $validator->errors()->messages());
        $this->assertEquals('The username field is required.', $validator->errors()->first('username'));
    }

    /** @test */
    public function passwordIsRequired()
    {
        $users_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make([], $users_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->messages());
        $this->assertEquals('The password field is required.', $validator->errors()->first('password'));
    }

    /** @test */
    public function usernameMustBeAString()
    {
        $users_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['username' => 12345], $users_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('username', $validator->errors()->messages());
        $this->assertEquals('The username field must be a string.', $validator->errors()->first('username'));
    }

    /** @test */
    public function passwordMustBeAString()
    {
        $users_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $validator = $validatorFactory->make(['password' => 12345], $users_req->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->messages());
        $this->assertEquals('The password field must be a string.', $validator->errors()->first('password'));
    }

    /** @test */
    public function validUsernameAndPasswordPassValidation()
    {
        $users_req = new UsersRequest();

        $validatorFactory = app(ValidatorFactory::class);

        $userDataTest = ['username' => 'testuser', 'password' => 'securepassword'];
        $validator = $validatorFactory->make($userDataTest, $users_req->rules());

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }
}
