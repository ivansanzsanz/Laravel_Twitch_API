<?php

namespace App\Http\Infrastructure\Controllers;

use App\Models\User;
use spec\GrumPHP\Linter\Json\JsonLintErrorSpec;

class AnalyticsController extends Controller
{
    public function getUserStreamers()
    {

        $users = User::with('followedStreamers')->get();

        $response = [];
        foreach ($users as $user) {
            $userData = [
                'username' => $user->username,
                'followedStreamers' => $user->followedStreamers->pluck('name')->toArray(),
            ];
            $response[] = $userData;
        }

        return response()->json($response, 200);
    }
}
