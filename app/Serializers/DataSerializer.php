<?php

namespace App\Serializers;

use Illuminate\Http\JsonResponse;

class DataSerializer
{
    public function serializeData($data): JsonResponse
    {
        return response()->json($data);
    }
}
