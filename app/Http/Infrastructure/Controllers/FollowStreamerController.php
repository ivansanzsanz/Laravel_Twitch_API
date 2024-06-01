<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\FollowStreamerService;
use App\Validators\FollowStreamerValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class FollowStreamerController
{

    private FollowStreamerService $followStreamerService;
    private FollowStreamerValidator $followStreamerValidator;
    private DataSerializer $dataSerializer;

    public function __construct(
        FollowStreamerService $followStreamerService,
        FollowStreamerValidator $followStreamerValidator,
        DataSerializer $dataSerializer
    ) {
        $this->followStreamerService = $followStreamerService;
        $this->followStreamerValidator = $followStreamerValidator;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->followStreamerValidator->validateFollowStreamerRequest($request)) {
            try {
                $followData = $request->only(['user_id', 'streamer_id']);

                $message = $this->followStreamerService->execute($followData['user_id'], $followData['streamer_id']);

                return $this->dataSerializer->serializeData([
                    'message' => $message
                ], 201);
            } catch (Exception $exception) {
                if ($exception->getMessage() === 'User already follows streamer') {
                    return response()->json(['error' => 'El usuario ya está siguiendo al streamer'], 409);
                }
                return response()->json(['error' => 'Error del servidor al seguir al streamer'], 500);
            }
        }

        return $this->followStreamerValidator->followStreamerResponseValidator($request);
    }
}
