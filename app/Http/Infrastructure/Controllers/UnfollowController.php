<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\UnfollowService;
use App\Validators\UnfollowValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnfollowController
{

    private UnfollowService $unfollowService;
    private UnfollowValidator $unfollowValidator;
    private DataSerializer $dataSerializer;

    public function __construct(
        UnfollowService   $unfollowService,
        UnfollowValidator $unfollowValidator,
        DataSerializer  $dataSerializer
    ) {
        $this->unfollowService = $unfollowService;
        $this->unfollowValidator = $unfollowValidator;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->unfollowValidator->validateUnfollowRequest($request)) {
            try {
                $followData = $request->only(['user_id', 'streamer_id']);

                $message = $this->unfollowService->execute($followData['user_id'], $followData['streamer_id']);

                return $this->dataSerializer->serializeData([
                    'message' => $message
                ], 201);
            } catch (Exception $exception) {
                if ($exception->getMessage() === 'User does not follows streamer') {
                    return response()->json(['error' => 'El usuario no está siguiendo al streamer'], 409);
                }
                return response()->json(['error' => 'Error del servidor al dejar de seguir al streamer'], 500);
            }
        }

        return $this->unfollowValidator->followResponseValidator($request);
    }
}
