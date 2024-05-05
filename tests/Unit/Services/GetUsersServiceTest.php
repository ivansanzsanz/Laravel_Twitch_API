<?php

namespace Services;

use Tests\TestCase;

class GetUsersServiceTest extends TestCase
{
    /**
     * @test
     */
    public function givenAnUserRequestWithAnIdValueReturnsCode200()
    {
        $response = $this->get('/analytics/users?id=123456789');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function givenAnUserRequestWithoutAnIdValueReturnsCode400()
    {
        $response = $this->get('/analytics/users?id=');

        $response->assertStatus(400);
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
