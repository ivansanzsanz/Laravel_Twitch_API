<?php

namespace App\Validators;

use http\Env\Request;

class FollowStreamerValidator
{

    public function validateFollowRequest(Request $request): bool
    {
        if (!$request->only(['user_id', 'streamer_id'])) {
            return false;
        }
        $data = $request->only(['user_id', 'streamer_id']);
        if (!is_numeric($data['user_id']) || !is_numeric($data['streamer_id'])) {
            return false;
        }
        return true;
    }

    public function followStreamerResponseValidator(Request $request): JsonResponse
    {
        if (!$request->input('user_id') || !$request->input('streamer_id')) {
            return response()->json([
                'error' => 'Los parámetros requeridos (user_id y streamer_id) no fueron proporcionados'
            ], 400);
        }
        $data = $request->only(['user_id', 'streamer_id']);
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
