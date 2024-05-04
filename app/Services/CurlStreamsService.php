<?php

namespace App\Services;

class CurlStreamsService
{
    public function curlStreams($access_token)
    {
        $client_id = env('CLIENT_ID');

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
