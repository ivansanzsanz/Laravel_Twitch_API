<?php

namespace App\Services;

class GetStreamersService
{
    private StreamersDataManager $streamersDataManager;

    public function __construct(StreamersDataManager $streamersDataManager)
    {
        $this->streamersDataManager = $streamersDataManager;
    }

    public function execute($streamerId): array
    {
        $user = $this->streamersDataManager->streamersDataProvider($streamerId);

        return $user['data'];
    }
}
