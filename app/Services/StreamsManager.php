<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use PhpParser\Node\Scalar\String_;

class StreamsManager
{
    private ApiClient $apiClient;
    private TwitchProvider $twitchProvider;

    public function __construct(ApiClient $apiClient, TwitchProvider $twitchProvider)
    {
        $this->apiClient = $apiClient;
        $this->twitchProvider = $twitchProvider;
    }

    public function getStreams(): array
    {
        $url = "https://api.twitch.tv/helix/streams";

        $header = array(
            'Authorization: Bearer ' . $this->twitchProvider->getTokenTwitch(),
        );

        $response = $this->apiClient->makeCurlCall($url, $header);

        $response = json_decode($response, true);

        if (!isset($response['data'])) {
            echo "Error en la peticion curl de los streams";
            exit;
        }

        return $response;
    }
}
