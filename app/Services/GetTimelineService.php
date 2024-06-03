<?php

namespace App\Services;

use Exception;

class GetTimelineService
{
    private TimelineDataManager $timelineManager;

    public function __construct(TimelineDataManager $streamsManager)
    {
        $this->timelineManager = $streamsManager;
    }

    public function execute($userData)
    {
        $responde = $this->timelineManager->timelineDataProvider($userData);

        return $responde;
    }
}
