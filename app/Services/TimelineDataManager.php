<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Http\Infrastructure\Clients\DBClient;

class TimelineDataManager
{
    private APIClient $apiClient;
    private DBClient $databaseClient;
    private TwitchProvider $twitchProvider;
    public function __construct(APIClient $apiClient, DBClient $databaseClient, TwitchProvider $twitchProvider)
    {
        $this->apiClient = $apiClient;
        $this->databaseClient = $databaseClient;
        $this->twitchProvider = $twitchProvider;
    }

    public function timelineDataProvider($userData): string
    {
        if (!$this->databaseClient->userIdAlreadyExists($userData)) {
            throw new Exception('User does not exist')  ;
        }
        $this->databaseClient->usersFollowedByUserID($userData);

        $this->databaseClient->insertUser($userData);

        return $userData['username'];
    }

}
