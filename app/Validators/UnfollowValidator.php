<?php

namespace App\Validators;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UnfollowRequest;
use Illuminate\Validation\Factory as ValidatorFactory;

class UnfollowValidator
{
    public function validateUnfollowRequest(Request $request): bool
    {
        $unfollowRequest = new UnfollowRequest();
        $validatorFactory = app(ValidatorFactory::class);

        if (!$request->only(['user_id', 'streamer_id'])) {
            return false;
        }

        $data = $request->only(['user_id', 'streamer_id']);
        $validator = $validatorFactory->make($data, $unfollowRequest->rules());
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function unfollowResponseValidator(Request $request): JsonResponse
    {
        $usersRequest = new UnfollowRequest();
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
                'error' => 'Los parámetros requeridos (user_id = int; streamer_id = string) no tienen el tipo correcto'
            ], 400);
        }
        return response()->json([
            'error' => 'no hay error'
        ], 200);
    }
}
