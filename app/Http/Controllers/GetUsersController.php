<?php

namespace App\Http\Controllers;

use App\Http\Validators\UserValidator;
use App\Services\GetUsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUsersController extends Controller
{
    private GetUsersService $getUsersService;
    private UserValidator $userValidator;

    public function __construct(GetUsersService $getUsersService, UserValidator $userValidator)
    {
        $this->getUsersService = $getUsersService;

        $this->userValidator = $userValidator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->userValidator->validateUserRequest($request)) {
            $userId = $request->query('id');
            $user = $this->getUsersService->execute($userId);

            return response()->json($user);
        }

        return $this->userValidator->responseValidator($request);
    }
}
