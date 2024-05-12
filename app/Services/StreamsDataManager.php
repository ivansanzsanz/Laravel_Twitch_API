<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\APIClient;

class StreamsDataManager
{
    private APIClient $apiClient;
    private TwitchProvider $twitchProvider;

    public function __construct(APIClient $apiClient, TwitchProvider $twitchProvider)
    {
        $this->apiClient = $apiClient;
        $this->twitchProvider = $twitchProvider;
    }

    public function streamsDataProvider(): array
    {
        $url = "https://api.twitch.tv/helix/streams";

        $header = array(
            'Authorization: Bearer ' . $this->twitchProvider->getToken(),
        );

        $response = $this->apiClient->makeCurlCall($url, $header);

        $response = json_decode($response, true);

        if (!isset($response['data'])) {
            echo response()->json([
                'error' => 'No se pueden devolver streams en este momento, inténtalo más tarde'
            ], 503);
            exit;
        }

        return $response;
    }
}
