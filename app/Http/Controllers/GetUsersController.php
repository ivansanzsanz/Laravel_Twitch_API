<?php

namespace App\Http\Controllers;

use App\Services\GetUsersService;
use App\Services\UserValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUsersController extends Controller
{
    private GetUsersService $getUsersService;

    public function __construct(GetUsersService $getUsersService)
    {
        $this->getUsersService = $getUsersService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validator = new UserValidator();
        $respuesta_validacion = $validator->validateUserRequest($request);

        if ($respuesta_validacion != 'id correcta') {
            return response()->json([
                'error' => $respuesta_validacion
            ], 400) ;
        }
        $userId = $request->query('id');
        $user = $this->getUsersService->execute($userId);

        return response()->json($user);
    }
}
