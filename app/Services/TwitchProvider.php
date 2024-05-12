<?php

namespace App\Services;

class TwitchProvider
{
    private ApiClient $apiClient;
    private DatabaseClient $databaseClient;

    public function __construct(ApiClient $apiClient, DatabaseClient $databaseClient)
    {
        $this->apiClient = $apiClient;
        $this->databaseClient = $databaseClient;
    }

    public function getToken(): string
    {
        if ($this->databaseClient->thereIsATokenInTheDB()) {
            return $this->databaseClient->getTokenFromDatabase();
        }

        $url = 'https://id.twitch.tv/oauth2/token';

        $getTokenResponse = $this->apiClient->getTokenTwitch($url);

        $decodedTokenResponse = json_decode($getTokenResponse, true);

        if (!isset($decodedTokenResponse['access_token'])) {
            echo response()->json([
                'error' => 'No se puede establecer conexión con Twitch en este momento'
            ], 503);
            exit;
        }

        $token = $decodedTokenResponse['access_token'];

        $this->databaseClient->insertTokenInDatabase($token);

        return $token;
    }
}
