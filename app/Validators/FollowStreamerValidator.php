<?php

namespace App\Validators;

use App\Http\Requests\FollowRequest;
use App\Http\Requests\UsersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;

class FollowStreamerValidator
{

    public function validateFollowRequest(Request $request): bool
    {
        $followRequest = new FollowRequest();
        $validatorFactory = app(ValidatorFactory::class);

        if (!$request->only(['user_id', 'streamer_id'])) {
            return false;
        }

        $data = $request->only(['user_id', 'streamer_id']);
        $validator = $validatorFactory->make($data, $followRequest->rules());
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function followStreamerResponseValidator(Request $request): JsonResponse
    {
        $usersRequest = new UsersRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->input('user_id') || !$request->input('streamer_id')) {
            return response()->json([
                'error' => 'Los parámetros requeridos (user_id y streamer_id) no fueron proporcionados'
            ], 400);
        }
        $data = $request->only(['user_id', 'streamer_id']);
        $validator = $validatorFactory->make($data, $usersRequest->rules());

        if (!is_numeric($data['user_id']) || !is_string($data['streamer_id'])) {
            return response()->json([
                'error' => 'Los parámetros requeridos (user_id = int y streamer_id = string) deben ser correctamente tipados'
            ], 400);
        }
        return response()->json([
            'error' => 'no hay error'
        ], 200);
    }
}
