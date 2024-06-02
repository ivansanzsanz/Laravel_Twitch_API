<?php

namespace App\Services;

use Exception;

class UnfollowService
{
    private FollowDataManager $followsDataManager;

    public function __construct(FollowDataManager $followsDataManager)
    {
        $this->followsDataManager = $followsDataManager;
    }

    /**
     * @throws Exception
     */
    public function execute($user_id, $streamer_id): string
    {
        return $this->followsDataManager->unfollowDataProvider($user_id, $streamer_id);
    }
}
