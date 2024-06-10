<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\CreateUsersService;
use App\Validators\UserValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateUsersController
{
    private CreateUsersService $createUsersService;
    private UserValidator $userValidator;
    private DataSerializer $dataSerializer;

    public function __construct(
        CreateUsersService $createUsersService,
        UserValidator $userValidator,
        DataSerializer $dataSerializer
    ) {
        $this->createUsersService = $createUsersService;
        $this->userValidator = $userValidator;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->userValidator->validateUserRequest($request)) {
            try {
                $userData = $request->only(['username', 'password']);

                $username = $this->createUsersService->execute($userData);

                return $this->dataSerializer->serializeData([
                    'username' => $username,
                    'message' => 'Usuario creado correctamente'
                ], 201);
            } catch (Exception $exception) {
                if ($exception->getMessage() === 'Username already exists') {
                    return response()->json(['error' => 'El nombre de usuario ya está en uso'], 409);
                }
                return response()->json(['error' => 'Error del servidor al crear el usuario'], 500);
            }
        }

        return $this->userValidator->userResponseValidator($request);
    }
}
