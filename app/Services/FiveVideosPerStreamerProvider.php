<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\APIClient;

class FiveVideosPerStreamerProvider
{
    private APIClient $apiClient;
    private TwitchProvider $twitchProvider;

    public function __construct(APIClient $apiClient, TwitchProvider $twitchProvider)
    {
        $this->apiClient = $apiClient;
        $this->twitchProvider = $twitchProvider;
    }
    public function getFiveVideosPerStreamer($streamers): array
    {
        $header = array(
        'Authorization: Bearer ' . $this->twitchProvider->getToken(),
        );
        $allStreams = array();
        foreach ($streamers as $streamer) {
            $url = "https://api.twitch.tv/helix/videos?user_id=" . $streamer;
            $response = $this->apiClient->makeCurlCall($url, $header);
            //dd(array_slice(json_decode($response, true)['data'], 0, 5));
            $videosStreamer = array_slice(json_decode($response, true)['data'], 0, 5);
            for ($i = 0; $i < 5; $i++) {
                $videoStreamer = array(
                    "streamerId" => $videosStreamer[$i]['id'],
                    "streamerName" => $videosStreamer[$i]['user_name'],
                    "title" => $videosStreamer[$i]['title'],
                    "viewerCount" => $videosStreamer[$i]['view_count'],
                    "startedAt" => $videosStreamer[$i]['created_at']
                );
                $allStreams[] = $videoStreamer;
            }
            usort($allStreams, function ($first, $second) {
                return strtotime($second['startedAt']) - strtotime($first['startedAt']);
            });
        };
        return $allStreams;
    }
}
