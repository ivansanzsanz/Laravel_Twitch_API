<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\DBClient;
use Exception;

class UnfollowDataManager
{

    private DBClient $databaseClient;

    public function __construct(DBClient $databaseClient)
    {
        $this->databaseClient = $databaseClient;
    }

    /**
     * @throws Exception
     */
    public function unfollowDataProvider($user_id, $streamer_id): string
    {
        if (!$this->databaseClient->userFollowsStreamer($user_id, $streamer_id)) {
            throw new Exception('User does not follows streamer');
        }

        $this->databaseClient->deleteFollow($user_id, $streamer_id);

        return "Dejaste de seguir a : $streamer_id";
    }

}
