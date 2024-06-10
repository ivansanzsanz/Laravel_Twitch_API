<?php

namespace App\Validators;

use App\Http\Requests\TimelineRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;

class TimelineValidator
{
    public function validateTimelineRequest(Request $request): bool
    {
        $timelineRequest = new TimelineRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->only(['userId'])) {
            return false;
        }
        $data = $request->only(['userId']);
        $validator = $validatorFactory->make($data, $timelineRequest->rules());
        if ($validator->fails()) {
            return false;
        }
        return true;
    }

    public function timelineResponseValidator(Request $request): JsonResponse
    {
        $timelineRequest = new TimelineRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->input('userId')) {
            return response()->json([
                'error' => 'El parametro requerido (userID) no fue proporcionado'
            ], 400);
        }
        $data = $request->only(['userId']);
        $validator = $validatorFactory->make($data, $timelineRequest->rules());

        if ($validator->fails()) {
            return response()->json([
                'error' => 'El parametro requerido (userID) debe de ser cadena de texto'
            ], 400);
        }
        return response()->json([
            'error' => 'no hay error'
        ], 200);
    }
}
