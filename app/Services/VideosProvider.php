<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\APIClient;

class VideosProvider
{
    private APIClient $apiClient;
    private TwitchProvider $twitchProvider;

    public function __construct(APIClient $apiClient, TwitchProvider $twitchProvider)
    {
        $this->apiClient = $apiClient;
        $this->twitchProvider = $twitchProvider;
    }

    public function getVideos($game)
    {
        $url = "https://api.twitch.tv/helix/videos?game_id=" . urlencode($game['id']) . "&sort=views&first=40";

        $header = array(
            'Authorization: Bearer ' . $this->twitchProvider->getToken(),
        );

        $response = $this->apiClient->makeCurlCall($url, $header);

        $response = json_decode($response, true);
        foreach ($response['data'] as &$videodata) {
            $videodata['game_id'] = $game['id'];
            $videodata['game_name'] = $game['name'];
        };

        return $this->getStreamerWithMostViews($response);
    }

    public function getStreamerWithMostViews($allVideos)
    {

        $result = array();

        foreach ($allVideos['data'] as $video) {
            $game_id = $video['game_id'];
            $game_name = $video['game_name'];
            $user_name = $video['user_name'];
            $view_count = $video['view_count'];
            $duration = $video['duration'];
            $created_at = $video['created_at'];

            if (isset($result[$user_name])) {
                $result[$user_name]['total_videos']++;
                $result[$user_name]['total_views'] += $view_count;
                if ($view_count > $result[$user_name]['most_viewed_views']) {
                    $result[$user_name]['most_viewed_title'] = $video['title'];
                    $result[$user_name]['most_viewed_views'] = $view_count;
                    $result[$user_name]['most_viewed_duration'] = $duration;
                    $result[$user_name]['most_viewed_created_at'] = $created_at;
                }
            }
            if (!isset($result[$user_name])) {
                $result[$user_name] = array(
                    'game_id' => $game_id,
                    'game_name' => $game_name,
                    'user_name' => $user_name,
                    'total_videos' => 1,
                    'total_views' => $view_count,
                    'most_viewed_title' => $video['title'],
                    'most_viewed_views' => $view_count,
                    'most_viewed_duration' => $duration,
                    'most_viewed_created_at' => $created_at
                );
            }
        }

        $result = array_values($result);
        usort($result, function ($first, $second) {
            return $second['total_views'] - $first['total_views'];
        });

        return $result[0];
    }
}
