<?php

namespace App\Services;

class GetTopsOfTheTopsService
{
    private TopsOfTheTopsDataManager $topsDataManager;

    public function __construct(TopsOfTheTopsDataManager $topsDataManager)
    {
        $this->topsDataManager = $topsDataManager;
    }

    public function execute($time): array
    {
        return $this->topsDataManager->topsOfTheTopsDataProvider($time);
    }
}
