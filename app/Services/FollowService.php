<?php

namespace App\Services;

use Exception;

class FollowService
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
        return $this->followsDataManager->followDataProvider($user_id, $streamer_id);
    }
}
