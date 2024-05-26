<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\GetTopsOfTheTopsService;
use App\Validators\TopsOfTheTopsValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetTopsOfTheTopsController extends Controller
{
    private GetTopsOfTheTopsService $getTopsService;
    private DataSerializer $dataSerializer;
    private TopsOfTheTopsValidator $topsValidator;

    public function __construct(
        GetTopsOfTheTopsService $getTopsService,
        DataSerializer $dataSerializer,
        TopsOfTheTopsValidator $topsValidator
    ) {
        $this->getTopsService = $getTopsService;
        $this->dataSerializer = $dataSerializer;
        $this->topsValidator = $topsValidator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->topsValidator->validateTopsOfTheTopsRequest($request)) {
            $time = $this->setTime($request);
            $user = $this->getTopsService->execute($time);

            return $this->dataSerializer->serializeData($user);
        }

        return $this->topsValidator->topsOfTheTopsResponseValidator($request);
    }

    public function setTime($request): int
    {
        if ($request->has('since')) {
            return intval($request->query('since'));
        }
        return 600;
    }
}
