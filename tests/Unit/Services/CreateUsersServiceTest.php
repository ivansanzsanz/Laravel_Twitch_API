<?php

namespace Tests\Unit\Services;

use App\Services\CreateUsersService;
use App\Services\UsersDataManager;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateUsersServiceTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function executeTest()
    {
        $mockery = new Mockery();
        $usersDataManager = $mockery->mock(UsersDataManager::class);
        $userDataExpected = array(
            'username' => 'username',
            'password' => 'password'
        );
        $usernameExpected = 'username';

        $usersDataManager
            ->expects('usersDataProvider')
            ->with($userDataExpected)
            ->once()
            ->andReturn($usernameExpected);

        $createUsersService = new CreateUsersService($usersDataManager);
        $result = $createUsersService->execute($userDataExpected);

        $this->assertEquals($result, $usernameExpected);
    }
}
