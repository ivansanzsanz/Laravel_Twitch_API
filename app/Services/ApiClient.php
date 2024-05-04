<?php

namespace App\Services;

class ApiClient
{
    public function getToken($url): string
    {
        $client_id = env('CLIENT_ID');
        $client_secret = env('CLIENT_SECRET');

        $post_data = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'client_credentials'
        );

        //$url = 'https://id.twitch.tv/oauth2/token'

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $url);
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

        curl_setopt(
            $ch1,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/x-www-form-urlencoded'
            )
        );

        $response1 = curl_exec($ch1);

        if (curl_errno($ch1)) {
            echo 'Error: ' . curl_error($ch1);
        }

        curl_close($ch1);

        return $response1;
    }

    public function makeCurlCall($url, $header)
    {
        $client_id = env('CLIENT_ID');

        $ch2 = curl_init();

        $header[] = 'Client-Id: ' . $client_id;
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_HTTPGET, true);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $header);

        $response2 = curl_exec($ch2);

        if (curl_errno($ch2)) {
            return 'Error: ' . curl_error($ch2);
        }

        curl_close($ch2);

        return $response2;
    }
}
