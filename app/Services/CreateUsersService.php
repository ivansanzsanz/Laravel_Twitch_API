<?php

namespace App\Services;

use Exception;

class CreateUsersService
{
    private UsersDataManager $usersDataManager;

    public function __construct(UsersDataManager $usersDataManager)
    {
        $this->usersDataManager = $usersDataManager;
    }

    /**
     * @throws Exception
     */
    public function execute($userData): string
    {
        return $this->usersDataManager->usersDataProvider($userData);
    }
}
