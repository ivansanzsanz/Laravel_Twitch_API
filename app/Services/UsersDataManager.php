<?php

namespace App\Services;

class UsersDataManager
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

    public function userDataProvider($userId): array
    {
        $result = $this->databaseClient->getUserFromDatabase($userId);

        if ($result !== null) {
            $dataArray = array();

            $dataArray['data'][0] = $result;

            return $dataArray;
        }

        $url = "https://api.twitch.tv/helix/users?id=" . urlencode($userId);

        $header = array(
            'Authorization: Bearer ' . $this->twitchProvider->getToken(),
        );

        $response = $this->apiClient->makeCurlCall($url, $header);

        $response = json_decode($response, true);

        if (!isset($response['data'])) {
            echo response()->json([
                'error' => 'No se pueden devolver usuarios en este momento, inténtalo más tarde'
            ], 503);
            exit;
        }

        $this->databaseClient->insertUserInDatabase($response);

        return $response;
    }
}
