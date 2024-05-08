<?php

namespace App\Services;

class UsersManager
{
    private string $token;
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getUserById($userId): array
    {
        $url = "https://api.twitch.tv/helix/users?id=" . urlencode($userId);
        $this->getTokenTwitch();

        $header = array(
            'Authorization: Bearer ' . $this->token,
        );


        $response = $this->apiClient->makeCurlCall($url, $header);

        //dd($response);

        $response = json_decode($response, true);



        if (!isset($response['data'])) {
            echo "Error en la peticion curl del user";
            exit;
        }

        return $response;
    }

    private function getTokenTwitch(): string
    {
        $url = 'https://id.twitch.tv/oauth2/token';

        $response = $this->apiClient->getToken($url);

        $decodedResponse = json_decode($response, true);

        if (!isset($decodedResponse['access_token'])) {
            echo "Error en la peticion curl del token";
            exit;
        }

        $this->token = $decodedResponse['access_token'];

        return $this->token;
    }
}
