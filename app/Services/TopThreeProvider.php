<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\APIClient;

class TopThreeProvider
{
    private APIClient $apiClient;
    private TwitchProvider $twitchProvider;

    public function __construct(APIClient $apiClient, TwitchProvider $twitchProvider)
    {
        $this->apiClient = $apiClient;
        $this->twitchProvider = $twitchProvider;
    }

    public function getTopThree()
    {

        $url = "https://api.twitch.tv/helix/games/top";

        $header = array(
            'Authorization: Bearer ' . $this->twitchProvider->getToken(),
        );

        $response = $this->apiClient->makeCurlCall($url, $header);

        $response = json_decode($response, true);

        if (!isset($response['data'])) {
            echo response()->json([
                'error' => 'No se pueden devolver topsofthetops en este momento, inténtalo más tarde'
            ], 503);
            exit;
        }

        unset($response['pagination']);

        $response['data'] = array_slice($response['data'], 0, 3);

        return $response;
    }
}
