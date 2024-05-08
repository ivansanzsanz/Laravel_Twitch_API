<?php

namespace Services;

use App\Http\Requests\UsersRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class GetUsersServiceTest extends TestCase
{
    /**
     * @test
     */
    public function givenAnUserRequestWithAnIdValueReturnsCode200()
    {
        $response = $this->get('/analytics/users?id=417603922');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function givenAnUserRequestWithoutAnIdValueReturnsCode400()
    {
        $response = $this->get('/analytics/users?id=');

        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function givenAnUserRequestWithoutIdReturnsCode400()
    {
        $response = $this->get('/analytics/users');

        $response->assertStatus(400);
    }
}
