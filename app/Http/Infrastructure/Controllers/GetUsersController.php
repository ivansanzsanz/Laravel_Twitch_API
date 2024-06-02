<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\GetUsersService;
use App\Validators\UserValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUsersController extends Controller
{
    private GetUsersService $getUsersService;
    private UserValidator $userValidator;

    private DataSerializer $dataSerializer;

    public function __construct(
        GetUsersService $getUsersService,
        UserValidator $userValidator,
        DataSerializer $dataSerializer
    ) {
        $this->getUsersService = $getUsersService;
        $this->userValidator = $userValidator;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->userValidator->validateUserRequest($request)) {
            $userId = $request->query('id');
            $user = $this->getUsersService->execute($userId);

            return $this->dataSerializer->serializeData($user);
        }

        return $this->userValidator->responseValidator($request);
    }
}
