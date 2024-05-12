<?php

namespace App\Services;

class StreamsDataManager
{
    private ApiClient $apiClient;
    private TwitchProvider $twitchProvider;

    public function __construct(ApiClient $apiClient, TwitchProvider $twitchProvider)
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
