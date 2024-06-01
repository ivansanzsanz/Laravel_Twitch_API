<?php

namespace App\Validators;

use App\Http\Requests\UsersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;

class UserValidator
{
    public function validateUserRequest(Request $request): bool
    {
        $usersRequest = new UsersRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->only(['username', 'password'])) {
            return false;
        }
        $data = $request->only(['username', 'password']);
        $validator = $validatorFactory->make($data, $usersRequest->rules());
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function userResponseValidator(Request $request): JsonResponse
    {
        $usersRequest = new UsersRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->input('username') || !$request->input('password')) {
            return response()->json([
                'error' => 'Los parámetros requeridos (username y password) no fueron proporcionados'
            ], 400);
        }
        $data = $request->only(['username', 'password']);
        $validator = $validatorFactory->make($data, $usersRequest->rules());

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Los parámetros requeridos (username y password) deben ser cadenas de texto'
            ], 400);
        }
        return response()->json([
            'error' => 'no hay error'
        ], 200);
    }
}
