<?php

namespace App\Services;

class TwitchProvider
{
    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getTokenTwitch(): string
    {
        $url = 'https://id.twitch.tv/oauth2/token';

        $getTokenResponse = $this->apiClient->getToken($url);

        $decodedTokenResponse = json_decode($getTokenResponse, true);

        if (!isset($decodedTokenResponse['access_token'])) {
            echo "Error en la peticion curl del token";
            exit;
        }

        $this->token = $decodedTokenResponse['access_token'];

        return $this->token;
    }
}
