<?php

namespace App\Services;

class DatabaseClient
{
    public $conn;

    public function __construct(DatabaseConnectionService $dbConnectionService)
    {
        $this->conn = $dbConnectionService->conn;
    }

    public function getUserFromDatabase($userId): array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM users_twitch WHERE id = ?");

        $stmt->bind_param("s", $userId);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function insertUserInDatabase($user): void
    {
        $newUser = "INSERT INTO users_twitch (id, login, display_name, type,
    broadcaster_type, description, profile_image_url, offline_image_url, view_count, created_at)
    VALUES (?,?,?,?,?,?,?,?,?,?);";

        $stmt = $this->conn->prepare($newUser);

        $userData = $user['data'][0];

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
    }
}
