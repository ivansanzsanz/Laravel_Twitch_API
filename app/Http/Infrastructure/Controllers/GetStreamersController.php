<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\GetStreamersService;
use App\Validators\StreamerValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetStreamersController extends Controller
{
    private GetStreamersService $getStreamersService;
    private StreamerValidator $streamerValidator;
    private DataSerializer $dataSerializer;

    public function __construct(
        GetStreamersService $getStreamersService,
        StreamerValidator $streamerValidator,
        DataSerializer $dataSerializer
    ) {
        $this->getStreamersService = $getStreamersService;
        $this->streamerValidator = $streamerValidator;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->streamerValidator->validateStreamerRequest($request)) {
            $userId = $request->query('id');
            $user = $this->getStreamersService->execute($userId);

            return $this->dataSerializer->serializeData($user);
        }

        return $this->streamerValidator->streamerResponseValidator($request);
    }
}
