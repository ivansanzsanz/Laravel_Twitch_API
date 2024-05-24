<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\DBClient;

class TopsOfTheTopsDataManager
{
    private DBClient $databaseClient;
    private TopThreeProvider $topThreeProvider;
    private VideosProvider $videosProvider;

    public function __construct(
        DBClient $databaseClient,
        TopThreeProvider $topThreeProvider,
        VideosProvider $videosProvider
    ) {
        $this->databaseClient = $databaseClient;
        $this->topThreeProvider = $topThreeProvider;
        $this->videosProvider = $videosProvider;
    }

    public function topsOfTheTopsDataProvider($time): array
    {
        $topStreamers = $this->databaseClient->thereIsTopStreamers();
        $response = $this->topThreeProvider->getTopThree();
        $date = date('Y-m-d H:i:s');

        $allIds = $this->databaseClient->getAllIds();

        if ($topStreamers) {
            $streamersInTime = $this->databaseClient->getInTimeStreamers($time);
            return $this->processTopStreamers($response['data'], $streamersInTime, $allIds, $date);
        }

        return $this->processNoTopStreamers($response['data'], $date);
    }

    private function processTopStreamers($games, $streamersInTime, $allIds, $date): array
    {
        $finalResult = [];

        foreach ($games as $game) {
            $inTimeStreamer = $this->findInTimeStreamer($game['id'], $streamersInTime);
            if ($inTimeStreamer) {
                $finalResult[] = $inTimeStreamer;
                continue;
            }
            $gameNotFoundVideos = $this->videosProvider->getVideos($game);
            $finalResult[] = $gameNotFoundVideos;
            $this->updateOrInsertStreamer($gameNotFoundVideos, $game['id'], $allIds, $date);
        }

        return $finalResult;
    }

    private function processNoTopStreamers($games, $date): array
    {
        $finalResult = [];

        foreach ($games as $game) {
            $gameVideos = $this->videosProvider->getVideos($game);
            $finalResult[] = $gameVideos;
            $this->databaseClient->insertStreamerInTops($gameVideos, $date);
        }

        return $finalResult;
    }

    private function updateOrInsertStreamer($gameNotFoundVideos, $gameId, $allIds, $date): void
    {
        if (in_array($gameId, $allIds)) {
            $this->databaseClient->updateStreamerInTops($gameNotFoundVideos, $date);
            return;
        }

        $this->databaseClient->insertStreamerInTops($gameNotFoundVideos, $date);
    }

    private function findInTimeStreamer($gameId, $streamersInTime): array|null
    {
        foreach ($streamersInTime as $inTimeStreamer) {
            if ($gameId === $inTimeStreamer['game_id']) {
                return $inTimeStreamer;
            }
        }
        return null;
    }
}
