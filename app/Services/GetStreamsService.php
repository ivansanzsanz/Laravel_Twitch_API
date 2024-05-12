<?php

namespace App\Services;

class GetStreamsService
{
    private StreamsDataManager $streamsManager;

    public function __construct(StreamsDataManager $streamsManager)
    {
        $this->streamsManager = $streamsManager;
    }

    public function execute()
    {
        $streamsResponse = $this->streamsManager->getStreams();

        $result = array();

        foreach ($streamsResponse['data'] as $item) {
            $result[] = array(
                'user_name' => $item['user_name'],
                'title' => $item['title']
            );
        }

        return $result;
    }
}
