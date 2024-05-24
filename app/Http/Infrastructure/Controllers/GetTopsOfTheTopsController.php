<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\GetTopsOfTheTopsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetTopsOfTheTopsController extends Controller
{
    private GetTopsOfTheTopsService $getTopsService;
    private DataSerializer $dataSerializer;

    public function __construct(
        GetTopsOfTheTopsService $getTopsService,
        DataSerializer $dataSerializer
    ) {
        $this->getTopsService = $getTopsService;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $time = $this->setTime($request);

        $topsOfTheTops = $this->getTopsService->execute($time);

        return $this->dataSerializer->serializeData($topsOfTheTops);
    }

    public function setTime($request): int
    {
        if ($request->has('since')) {
            return intval($request->query('since'));
        }
        return 600;
    }
}
