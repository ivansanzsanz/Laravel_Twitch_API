<?php

namespace App\Http\Controllers;

use App\Services\CurlStreamsService;
use Illuminate\Http\JsonResponse;

class StreamsController extends Controller
{
    private CurlStreamsService $curlStreamsService;

    public function __construct()
    {
        $this->curlStreamsService = new CurlStreamsService();
    }

    public function streams(): JsonResponse
    {
        $responseDecoded = $this->curlStreamsService->curlStreams();

        $finalResult = $this->resultArray($responseDecoded);

        return response()->json($finalResult);
    }

    public function resultArray($responseDecoded): array
    {
        $result = array();

        foreach ($responseDecoded['data'] as $item) {
            $result[] = array(
                'user_name' => $item['user_name'],
                'title' => $item['title']
            );
        }

        return array(
            'data' => $result
        );
    }
}
