<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\FollowService;
use App\Validators\FollowValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowController
{
    private FollowService $followService;
    private FollowValidator $followValidator;
    private DataSerializer $dataSerializer;

    public function __construct(
        FollowService $followService,
        FollowValidator $followValidator,
        DataSerializer $dataSerializer
    ) {
        $this->followService = $followService;
        $this->followValidator = $followValidator;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->followValidator->validateFollowRequest($request)) {
            try {
                $followData = $request->only(['user_id', 'streamer_id']);

                $message = $this->followService->execute($followData['user_id'], $followData['streamer_id']);

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

        return $this->followValidator->followResponseValidator($request);
    }
}
