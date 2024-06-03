<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Http\Infrastructure\Clients\DBClient;
use Exception;

class TimelineDataManager
{
    private DBClient $databaseClient;
    private TwitchProvider $twitchProvider;
    private FiveVideosPerStreamerProvider $fiveVideosProvider;
    public function __construct(FiveVideosPerStreamerProvider $fiveVideosProvider, DBClient $databaseClient)
    {
        $this->fiveVideosProvider = $fiveVideosProvider;
        $this->databaseClient = $databaseClient;
    }

    public function timelineDataProvider($userData): array
    {
        if (!$this->databaseClient->userIdAlreadyExists($userData)) {
            throw new Exception('User does not exist')  ;
        }
        $followedStreamers = $this->databaseClient->usersFollowedByUserID($userData);
        $videosFollowed = $this->fiveVideosProvider->getFiveVideosPerStreamer($followedStreamers);
        return $videosFollowed;
    }
}
