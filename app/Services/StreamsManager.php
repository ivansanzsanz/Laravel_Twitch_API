<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use PhpParser\Node\Scalar\String_;

class StreamsManager
{
    private string $token;
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getStreams(): array
    {
        $url = "https://api.twitch.tv/helix/streams";
        $this->getTokenTwitch();

        $header = array(
            'Authorization: Bearer ' . $this->token,
        );

        $response = $this->apiClient->makeCurlCall($url, $header);

        $response = json_decode($response, true);

        if (!isset($response['data'])) {
            echo "Error en la peticion curl de los streams";
            exit;
        }

        return $response;
    }

    //Sacar a TwitchProvider.php

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
