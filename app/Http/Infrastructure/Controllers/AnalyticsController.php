<?php

namespace App\Http\Infrastructure\Controllers;

use App\Models\User;
use Illuminate\Http\Response;

class AnalyticsController extends Controller
{
    public function getUserStreamers(): Response
    {
        try {
            $users = User::all();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error del servidor al obtener la lista de usuarios.'], 500);
        }
    }
}
