<?php

namespace App\Serializers;

use Illuminate\Http\JsonResponse;

class DataSerializer
{
    public function serializeData($data, $status): JsonResponse
    {
        return response()->json($data, $status);
    }
}
