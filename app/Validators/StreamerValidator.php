<?php

namespace App\Validators;

use App\Http\Requests\StreamersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;

class StreamerValidator
{
    public function validateStreamerRequest(Request $request): bool
    {
        $streamersRequest = new StreamersRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->query('id')) {
            return false;
        }
        $data = array(
            'id' => $request->query('id'),
        );
        $validator = $validatorFactory->make($data, $streamersRequest->rules());
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function streamerResponseValidator(Request $request): JsonResponse
    {
        $streamersRequest = new StreamersRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->query('id')) {
            return response()->json([
                'error' => 'id requerida en la URL'
            ], 400);
        }
        $data = array(
            'id' => $request->query('id'),
        );
        $validator = $validatorFactory->make($data, $streamersRequest->rules());
        if ($validator->fails()) {
            return response()->json([
                'error' => 'id no es numerica en la URL'
            ], 400);
        }
        return response()->json([
            'error' => 'no hay error'
        ], 200);
    }
}
