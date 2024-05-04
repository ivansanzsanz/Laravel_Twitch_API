<?php

namespace App\Services;

class GetStreamsService
{
    private StreamsManager $streamsManager;

    public function __construct(StreamsManager $streamsManager)
    {
        $this->streamsManager = $streamsManager;
    }

    public function execute()
    {
        $streams = $this->streamsManager->getStreams();

        $result = array();

        foreach ($streams['data'] as $item) {
            $result[] = array(
                'user_name' => $item['user_name'],
                'title' => $item['title']
            );
        }

        return $result;
    }
}
