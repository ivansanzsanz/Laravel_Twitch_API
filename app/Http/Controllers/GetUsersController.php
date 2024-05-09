<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Services\GetUsersService;
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
        if (!$request->query('id')) {
            return response()->json([
                'error' => 'URL mal introducida falta o no es numerico la id'
            ], 400);
        }
        $userId = $request->query('id');
        $user = $this->getUsersService->execute($userId);

        return response()->json($user);
    }

    /*private CurlUsersService $curlUsersService;
    private DatabaseConnectionService $databaseConnection;

    public function __construct()
    {
        $this->curlUsersService = new CurlUsersService();
        $this->databaseConnection = new DatabaseConnectionService();
    }

    public function users(Request $request): JsonResponse
    {
        $conn = $this->databaseConnection->__construct();

        $userId = $request->query('id');

        $finalResult = $this->arrayResult($conn, $userId);

        return response()->json($finalResult);
    }

    public function arrayResult($conn, $userId): array
    {
        $result = $this->getUserById($conn, $userId);

        if ($result->num_rows <= 0) {
            $userData = $this->insertUser($conn, $userId);
            $response = $userData;
        } else {
            $response = $result->fetch_assoc();
        }

        $conn->close();

        return $response;
    }

    public function getUserById($conn, $userId): mysqli_result
    {
        $sql = "SELECT * FROM users_twitch WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function insertUser($conn, $userId): array
    {
        $newUser = "INSERT INTO users_twitch (id, login, display_name, type,
    broadcaster_type, desciption, profile_image_url, offline_image_url, view_count, created_at)
    VALUES (?,?,?,?,?,?,?,?,?,?);";
        $stmt = $conn->prepare($newUser);

        $responseDecoded = $this->curlUsersService->curlUsers($userId);

        $userData = $responseDecoded['data'][0];

        $stmt->bind_param(
            "ssssssssis",
            $userData['id'],
            $userData['login'],
            $userData['display_name'],
            $userData['type'],
            $userData['broadcaster_type'],
            $userData['description'],
            $userData['profile_image_url'],
            $userData['offline_image_url'],
            $userData['view_count'],
            $userData['created_at']
        );

        $stmt->execute();
        $stmt->close();

        return $userData;
    }*/
}
