<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\DBClient;
use Exception;

class UsersDataManager
{
    private DBClient $databaseClient;

    public function __construct(DBClient $databaseClient)
    {
        $this->databaseClient = $databaseClient;
    }

    /**
     * @throws Exception
     */
    public function usersDataProvider($userData): string
    {
        if ($this->databaseClient->usernameAlreadyExists($userData['username'])) {
            throw new Exception('Username already exists');
        }

        $this->databaseClient->insertUser($userData);

        return $userData['username'];
    }
}
