<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\GetStreamsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetStreamsController extends Controller
{
    private GetStreamsService $getStreamsService;
    private DataSerializer $dataSerializer;

    public function __construct(GetStreamsService $getStreamsService, DataSerializer $dataSerializer)
    {
        $this->getStreamsService = $getStreamsService;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $streams = $this->getStreamsService->execute();

        return $this->dataSerializer->serializeData($streams, 200);
    }
}
