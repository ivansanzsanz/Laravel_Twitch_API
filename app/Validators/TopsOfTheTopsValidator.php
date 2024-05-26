<?php

namespace App\Validators;

use App\Http\Requests\StreamersRequest;
use App\Http\Requests\TopsOfTheTopsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;

class TopsOfTheTopsValidator
{
    public function validateTopsOfTheTopsRequest(Request $request): bool
    {
        $topsOfTheTopsRequest = new TopsOfTheTopsRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->query('since')) {
            return true;
        }
        $data = array(
            'since' => $request->query('since'),
        );
        $validator = $validatorFactory->make($data, $topsOfTheTopsRequest->rules());
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function topsOfTheTopsResponseValidator(Request $request): JsonResponse
    {
        $topsOfTheTopsRequest = new TopsOfTheTopsRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->query('since')) {
            return response()->json([
                'error' => 'no hay error'
            ], 200);
        }
        $data = array(
            'since' => $request->query('since'),
        );
        $validator = $validatorFactory->make($data, $topsOfTheTopsRequest->rules());
        if ($validator->fails()) {
            return response()->json([
                'error' => 'since no es numerico en la URL'
            ], 400);
        }
        return response()->json([
            'error' => 'no hay error'
        ], 200);
    }
}
