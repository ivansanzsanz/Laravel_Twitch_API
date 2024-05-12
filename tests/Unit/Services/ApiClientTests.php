<?php

namespace Services;

use App\Http\Infrastructure\Clients\APIClient;
use Tests\TestCase;

class ApiClientTests extends TestCase
{
    /**
     * @test
     */
    public function givenApiClientInvalidTokenReturnsInvalidOAuthTokenMessage()
    {
        $apiClient = new APIClient();
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
        $apiClient = new APIClient();
        $badUrl = "hdfjskl";
        $token = $apiClient->getTokenTwitch("https://id.twitch.tv/oauth2/token");

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
        $apiClient = new APIClient();
        $badUrl = "hdfjskl";
        $response = $apiClient->getTokenTwitch($badUrl);
        $this->assertFalse($response);
    }
}
