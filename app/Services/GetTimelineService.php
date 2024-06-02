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
        //CAMBIAR
        $responde = $this->timelineManager->timelineDataProvider($userData);
        /*$streamsResponse = $this->timelineManager->streamsDataProvider();

        $result = array();

        foreach ($streamsResponse['data'] as $item) {
            $result[] = array(
                'user_name' => $item['user_name'],
                'title' => $item['title']
            );
        }*/

        return $responde;
    }
}
