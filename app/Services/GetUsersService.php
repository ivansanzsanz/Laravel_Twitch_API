<?php

namespace App\Services;

class GetUsersService
{
    private UsersDataManager $usersManager;

    public function __construct(UsersDataManager $userManager)
    {
        $this->usersManager = $userManager;
    }

    public function execute($userId): array
    {
        $user = $this->usersManager->getUserById($userId);

        return $user['data'];
    }
}
