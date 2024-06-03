<?php

namespace App\Http\Infrastructure\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Exception;

abstract class Controller
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
