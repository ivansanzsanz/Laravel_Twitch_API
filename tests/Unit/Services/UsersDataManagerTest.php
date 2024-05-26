<?php

namespace Tests\Unit\Services;

use App\Http\Infrastructure\Clients\DBClient;
use App\Services\UsersDataManager;
use Mockery;
use PHPUnit\Framework\TestCase;

class UsersDataManagerTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function userDataProviderTest()
    {
        $mockery = new Mockery();
        $databaseClient = $mockery->mock(DBClient::class);
        $userDataExpected = array(
            'username' => 'username',
            'password' => 'password'
        );
        $usernameExpected = 'username';

        $databaseClient
            ->expects('usernameAlreadyExists')
            ->with($usernameExpected)
            ->once()
            ->andReturn(false);
        $databaseClient
            ->expects('insertUser')
            ->with($userDataExpected)
            ->once();

        $usersDataManager = new UsersDataManager($databaseClient);
        $result = $usersDataManager->usersDataProvider($userDataExpected);

        $this->assertEquals($result, $usernameExpected);
    }
}
