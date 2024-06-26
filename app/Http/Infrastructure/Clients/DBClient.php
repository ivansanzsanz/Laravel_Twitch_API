<?php

namespace App\Http\Infrastructure\Clients;

use App\Services\DatabaseConnectionService;
use DateTime;

class DBClient
{
    public $conn;

    public function __construct(DatabaseConnectionService $dbConnectionService)
    {
        $this->conn = $dbConnectionService->conn;
    }

    public function thereIsATokenInTheDB(): bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM tokens_twitch");

        $stmt->execute();

        $result = $stmt->get_result();

        return ($result->num_rows > 0);
    }

    public function getTokenFromDatabase(): string
    {
        $stmt = $this->conn->prepare("SELECT * FROM tokens_twitch");

        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return $result['token'];
    }


    public function insertTokenInDatabase($token): void
    {
        $client_id = env('CLIENT_ID');

        $stmt2 = $this->conn->prepare("insert into tokens_twitch (user_id, token) values (?,?)");

        $stmt2->bind_param("ss", $client_id, $token);

        $stmt2->execute();
    }

    public function getStreamerFromDatabase($userId): array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM streamers_twitch WHERE id = ?");

        $stmt->bind_param("s", $userId);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function insertStreamerInDatabase($streamer): void
    {
        $newStreamer = "INSERT INTO streamers_twitch (id, login, display_name, type,
    broadcaster_type, description, profile_image_url, offline_image_url, view_count, created_at)
    VALUES (?,?,?,?,?,?,?,?,?,?);";

        $stmt = $this->conn->prepare($newStreamer);

        $streamerData = $streamer['data'][0];

        $stmt->bind_param(
            "ssssssssis",
            $streamerData['id'],
            $streamerData['login'],
            $streamerData['display_name'],
            $streamerData['type'],
            $streamerData['broadcaster_type'],
            $streamerData['description'],
            $streamerData['profile_image_url'],
            $streamerData['offline_image_url'],
            $streamerData['view_count'],
            $streamerData['created_at']
        );

        $stmt->execute();
    }

    public function thereIsTopStreamers(): bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM topsofthetops");

        $stmt->execute();

        $result = $stmt->get_result();

        return ($result->num_rows > 0);
    }

    public function getAllIds(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM topsofthetops");

        $stmt->execute();

        $result = $stmt->get_result();

        $allIds = array();
        while ($line = $result->fetch_assoc()) {
            $allIds[] = $line['game_id'];
        }
        return $allIds;
    }

    public function getInTimeStreamers($time): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM topsofthetops");

        $stmt->execute();

        $result = $stmt->get_result();

        $inTime = array();

        while ($line = $result->fetch_assoc()) {
            $date1 = new DateTime($line['date']);
            $date2 = new DateTime();
            $difference = $date1->diff($date2);
            $minutes = ($difference->days * 24 * 60) + ($difference->h * 60) + $difference->i;
            if ($minutes < $time / 60) {
                unset($line['date']);
                $inTime[] = $line;
            }
        }

        return $inTime;
    }

    public function insertStreamerInTops($gameVideos, $date): void
    {
        $sql = "INSERT INTO topsofthetops VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssiisisss",
            $gameVideos['game_id'],
            $gameVideos['game_name'],
            $gameVideos['user_name'],
            $gameVideos['total_videos'],
            $gameVideos['total_views'],
            $gameVideos['most_viewed_title'],
            $gameVideos['most_viewed_views'],
            $gameVideos['most_viewed_duration'],
            $gameVideos['most_viewed_created_at'],
            $date
        );
        $stmt->execute();
    }

    public function updateStreamerInTops($gameVideos, $date): void
    {
        $sql = "UPDATE topsofthetops
                    SET user_name = ?, total_videos = ?, total_views = ?, most_viewed_title = ?,
                    most_viewed_views = ?, most_viewed_duration = ?, most_viewed_created_at = ?, date = ?
                    WHERE game_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "siisissss",
            $gameVideos['user_name'],
            $gameVideos['total_videos'],
            $gameVideos['total_views'],
            $gameVideos['most_viewed_title'],
            $gameVideos['most_viewed_views'],
            $gameVideos['most_viewed_duration'],
            $gameVideos['most_viewed_created_at'],
            $date,
            $gameVideos['game_id']
        );
        $stmt->execute();
    }

    public function usernameAlreadyExists($username): bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM users_twitch WHERE username = ?");

        $stmt->bind_param("s", $username);

        $stmt->execute();
        return ($stmt->get_result()->num_rows > 0);
    }
    public function userIdAlreadyExists($userId): bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM users_twitch WHERE user_id = ?");
        $stmt->bind_param("s", $userId);

        $stmt->execute();
        return ($stmt->get_result()->num_rows > 0);
    }
    public function usersFollowedByUserID($userId): array
    {
        $stmt = $this->conn->prepare("SELECT streamer_id FROM user_follows WHERE user_id = ?");

        $stmt->bind_param("s", $userId);

        $stmt->execute();

        $result = $stmt->get_result();
        $followedStreamers = array();

        while ($line = $result->fetch_assoc()) {
                $followedStreamers[] = $line['streamer_id'];
        }
        return $followedStreamers;
    }

    public function insertUser($userdata): void
    {
        $stmt = $this->conn->prepare("INSERT INTO users_twitch (username, password) VALUES (?, ?)");

        $stmt->bind_param("ss", $userdata['username'], $userdata['password']);

        $stmt->execute();
    }

    public function userFollowsStreamer($userId, $streamerId): bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM user_follows WHERE user_id = ? AND streamer_id = ?");

        $stmt->bind_param("is", $userId, $streamerId);

        $stmt->execute();

        return ($stmt->get_result()->num_rows > 0);
    }

    public function insertFollow($userId, $streamerId): void
    {
        $stmt = $this->conn->prepare("INSERT INTO user_follows VALUES (?, ?)");

        $stmt->bind_param("is", $userId, $streamerId);

        $stmt->execute();
    }

    public function deleteFollow($userId, $streamerId): void
    {
        $stmt = $this->conn->prepare("DELETE FROM user_follows WHERE user_id = ? AND streamer_id = ?");

        $stmt->bind_param("is", $userId, $streamerId);

        $stmt->execute();
    }
}
