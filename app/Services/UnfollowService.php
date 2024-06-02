<?php

namespace App\Services;

use Exception;

class UnfollowService
{
    private UnfollowDataManager $unfollowDataManager;

    public function __construct(UnfollowDataManager $unfollowDataManager)
    {
        $this->unfollowDataManager = $unfollowDataManager;
    }

    /**
     * @throws Exception
     */
    public function execute($user_id, $streamer_id): string
    {
        return $this->unfollowDataManager->unfollowDataProvider($user_id, $streamer_id);
    }
}
