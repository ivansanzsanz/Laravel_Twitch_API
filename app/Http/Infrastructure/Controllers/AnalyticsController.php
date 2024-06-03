<?php

namespace App\Http\Infrastructure\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function getUserStreamers(): JsonResponse
    {
        try {
            $users = User::all();

            return response()->json($users);
        } catch (Exception) {
            return response()->json(['error' => 'Error del servidor al obtener la lista de usuarios.'], 500);
        }
    }
}
