<?php

namespace App\Services;

class FollowStreamerService
{
    private FollowsDataManager $followsDataManager;

    public function __construct(FollowsDataManager $followsDataManager)
    {
        $this->$followsDataManager = $followsDataManager;
    }

    /**
     * @throws Exception
     */
    public function execute($user_id, $streamer_id): string
    {
        return $this->usersDataManager->usersDataProvider($user_id, $streamer_id);
    }
}
