<?php

namespace Services;

use App\Http\Infrastructure\Clients\APIClient;
use App\Services\TwitchProvider;
use App\Services\VideosProvider;
use Mockery;
use Tests\TestCase;

class VideosProviderTest extends TestCase
{
    private $apiClient;
    private $twitchProvider;
    private $videosProvider;

    protected function setUp(): void
    {
        $mockery = new Mockery();
        $this->apiClient = $mockery->mock(APIClient::class);
        $this->twitchProvider = $mockery->mock(TwitchProvider::class);

        $this->videosProvider = new VideosProvider($this->apiClient, $this->twitchProvider);
    }

    public function testGetVideos()
    {
        $game = array([
            'id' => '1234',
            'name' => 'Valorant'
        ]);
        $apiResponse = [
            'data' => [
                [
                    'user_name' => 'StarWraith',
                    'view_count' => 7369,
                    'duration' => '31h',
                    'created_at' => '2023-01-01T00:00:00Z',
                    'title' => 'Valo y lo que surja'
                ],
                [
                    'user_name' => 'Danikongi',
                    'view_count' => 750,
                    'duration' => '2h',
                    'created_at' => '2023-01-02T00:00:00Z',
                    'title' => 'Mejorando el KDA y HS rate'
                ],
                [
                    'user_name' => 'Mixwell',
                    'view_count' => 22627,
                    'duration' => '6h',
                    'created_at' => '2023-01-03T00:00:00Z',
                    'title' => 'Heretics vs Vitality'
                ]
            ]
        ];
        $this->twitchProvider
            ->expects('getToken')
            ->once()
            ->andReturn('tokenFalso');
        $this->apiClient
            ->expects('makeCurlCall')
            ->once()
            ->andReturn(json_encode($apiResponse));
        $result = $this->videosProvider->getVideos($game);
        $this->assertEquals('Mixwell', $result['user_name']);
        $this->assertEquals(22627, $result['total_views']);
        $this->assertEquals('Heretics vs Vitality', $result['most_viewed_title']);
    }
}
