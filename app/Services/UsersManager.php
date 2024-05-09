<?php

namespace App\Services;

class UsersManager
{
    private string $token;
    private ApiClient $apiClient;
    private DatabaseClient $databaseClient;

    public function __construct(ApiClient $apiClient, DatabaseClient $databaseClient)
    {
        $this->apiClient = $apiClient;

        $this->databaseClient = $databaseClient;
    }

    public function getUserById($userId): array
    {
        /*if($this->databaseClient->usersInDatabase($userId)){
            $dataArray = array();
            $result = $this->databaseClient->getUserFromDatabase($userId);
            while ($user = $result->fetch_assoc()) {
                $dataArray['data'][0] = $user;
            }
            return $dataArray;
        }*/

        //GetUsersService.php

        $result = $this->databaseClient->getUserFromDatabase($userId);

        if ($result !== null) {
            $dataArray = array();

            $dataArray['data'][0] = $result;

            return $dataArray;
        }

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

        $this->databaseClient->insertUserInDatabase($response);

        return $response;
    }

    public function getTokenTwitch(): string
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
