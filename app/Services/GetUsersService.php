<?php

namespace App\Services;

class GetUsersService
{
    private UsersManager $usersManager;

    public function __construct(UsersManager $userManager)
    {
        $this->usersManager = $userManager;
    }

    public function execute($userId): array
    {
        $user = $this->usersManager->getUserById($userId);

        return $user['data'];
    }
}
