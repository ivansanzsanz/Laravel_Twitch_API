<?php

namespace Services;

use App\Http\Requests\UsersRequest;
use App\Models\User;
use App\Services\ApiClient;
use Illuminate\Support\Facades\Validator;
use App\Services\GetUsersService;
use App\Services\UsersManager;
use Mockery;
use Tests\TestCase;

class GetUsersServiceTest extends TestCase
{
    /**
     * @test
     */
    public function executeTest()
    {
        $mockery = new Mockery();
        $usersManager = $mockery->mock(UsersManager::class);
        $userExpected = array('data' => [[
            'id' => '123456789',
            'login' => 'login',
            'display_name' => 'display_name',
            'type' => '',
            'broadcaster_type' => '',
            'description' => 'description',
            'profile_image_url' => 'profile_image_url',
            'offline_image_url' => '',
            'view_count' => 0,
            'created_at' => '05-05-2024'
        ]]);

        $usersManager
            ->expects('getUserById')
            ->with('123456789')
            ->once()
            ->andReturn($userExpected);

        $getUsersService = new GetUsersService($usersManager);
        $result = $getUsersService->execute('123456789');

        $this->assertEquals($result, array(['id' => '123456789',
            'login' => 'login',
            'display_name' => 'display_name',
            'type' => '',
            'broadcaster_type' => '',
            'description' => 'description',
            'profile_image_url' => 'profile_image_url',
            'offline_image_url' => '',
            'view_count' => 0,
            'created_at' => '05-05-2024'
        ]));
    }
}
