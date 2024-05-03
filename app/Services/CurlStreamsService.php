<?php

namespace App\Services;

class CurlStreamsService
{
    private TokenService $tokenService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    public function curlStreams()
    {
        $client_id = env('CLIENT_ID');

        $access_token = $this->tokenService->token();

        $ch2 = curl_init();

        $url = "https://api.twitch.tv/helix/streams";

        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_HTTPGET, true);

        curl_setopt(
            $ch2,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: ' . 'Bearer ' . $access_token,
                'Client-Id: ' . $client_id
            )
        );

        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

        $response2 = curl_exec($ch2);

        if (curl_errno($ch2)) {
            return 'Error: ' . curl_error($ch2);
        }

        curl_close($ch2);

        return json_decode($response2, true);
    }
}
