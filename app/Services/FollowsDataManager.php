<?php

namespace App\Services;

use App\Http\Infrastructure\Clients\DBClient;

class FollowsDataManager
{
    private DBClient $databaseClient;

    public function __construct(DBClient $databaseClient)
    {
        $this->databaseClient = $databaseClient;
    }

    /**
     * @throws Exception
     */
    public function followsDataProvider($user_id, $streamer_id): string
    {
        if ($this->databaseClient->userAlreadyFollowsStreamer($user_id, $streamer_id)) {
            throw new Exception('User already follows streamer');
        }

        $this->databaseClient->insertFollow($user_id, $streamer_id);

        return "Ahora sigues a : $streamer_id";
    }
}
