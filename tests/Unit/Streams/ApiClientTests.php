<?php

namespace Streams;

use App\Services\ApiClient;
use Tests\TestCase;

class ApiClientTests extends TestCase
{
    /**
     * @test
     */
    public function givenApiClientInvalidTokenReturnsInvalidOAuthTokenMessage()
    {
        $apiClient = new ApiClient();
        $url = "https://api.twitch.tv/helix/streams";
        $badToken = "iHateKnitters";

        $header = array(
            'Authorization: Bearer ' . $badToken,
        );

        $response = $apiClient->makeCurlCall($url, $header);
        $response = json_decode($response, true);
        $this->assertEquals("Invalid OAuth token", $response["message"]);
    }

    /**
     * @test
     */
    public function givenApiClientInvalidUrlReturnsFalse()
    {
        $apiClient = new ApiClient();
        $badUrl = "hdfjskl";
        $token = $apiClient->getToken("https://id.twitch.tv/oauth2/token");

        $header = array(
            'Authorization: Bearer ' . $token,
        );

        $response = $apiClient->makeCurlCall($badUrl, $header);
        $this->assertFalse($response);
    }

    /**
     * @test
     */
    public function givenBadUrlInGetTokenReturnsFalse()
    {
        $apiClient = new ApiClient();
        $badUrl = "hdfjskl";
        $response = $apiClient->getToken($badUrl);
        $this->assertFalse($response);
    }
}
