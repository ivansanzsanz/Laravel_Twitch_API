<?php

namespace App\Services;

class UsersManager
{
    private ApiClient $apiClient;
    private DatabaseClient $databaseClient;
    private TwitchProvider $twitchProvider;

    public function __construct(ApiClient $apiClient, DatabaseClient $databaseClient, TwitchProvider $twitchProvider)
    {
        $this->apiClient = $apiClient;
        $this->databaseClient = $databaseClient;
        $this->twitchProvider = $twitchProvider;
    }

    public function getUserById($userId): array
    {
        $result = $this->databaseClient->getUserFromDatabase($userId);

        if ($result !== null) {
            $dataArray = array();

            $dataArray['data'][0] = $result;

            return $dataArray;
        }

        $url = "https://api.twitch.tv/helix/users?id=" . urlencode($userId);

        $header = array(
            'Authorization: Bearer ' . $this->twitchProvider->getTokenTwitch(),
        );

        $response = $this->apiClient->makeCurlCall($url, $header);

        $response = json_decode($response, true);

        if (!isset($response['data'])) {
            echo "Error en la peticion curl del user";
            exit;
        }

        $this->databaseClient->insertUserInDatabase($response);

        return $response;
    }
}
