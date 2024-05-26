<?php

namespace Tests\Feature;

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\UsersDataManager;
use Mockery;
use Tests\TestCase;

class CreateUsersTest extends TestCase
{
    /**
     * @test
     */
    public function createUser()
    {
        $mockery = new Mockery();
        $databaseClient = $mockery->mock(DBClient::class);
        $this->app
            ->when(UsersDataManager::class)
            ->needs(DBClient::class)
            ->give(fn() => $databaseClient);
        $userdataExpected = array(
            'username' => 'username',
            'password' => 'password'
        );
        $resultExpected = '{"username":"username","message":"Usuario creado correctamente"}';

        $databaseClient
            ->expects('usernameAlreadyExists')
            ->with($userdataExpected['username'])
            ->once()
            ->andReturn(false);
        $databaseClient
            ->expects('insertUser')
            ->with($userdataExpected)
            ->once();

        $userResponse = $this->post('/analytics/users', $userdataExpected);

        $userResponse->assertStatus(201);
        $userResponse->assertContent($resultExpected);
    }
}
