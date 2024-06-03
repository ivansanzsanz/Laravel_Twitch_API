<?php

namespace App\Http\Infrastructure\Controllers;

use App\Serializers\DataSerializer;
use App\Services\GetTimelineService;
use App\Validators\TimelineValidator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class GetTimelimeController
{
    private GetTimelineService $getTimelineService;
    private TimelineValidator $timelineValidator;
    private DataSerializer $dataSerializer;

    public function __construct(
        GetTimelineService $getTimelineService,
        TimelineValidator $timelineValidator,
        DataSerializer $dataSerializer
    ) {
        $this->getTimelineService = $getTimelineService;
        $this->timelineValidator = $timelineValidator;
        $this->dataSerializer = $dataSerializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($this->timelineValidator->validateTimelineRequest($request)) {
            try {
                $timelineData = $request->only(['userId']);
                $response = $this->getTimelineService->execute($request->query('userId'));
                return $this->dataSerializer->serializeData([$response[0],
                ], 201);
            } catch (Exception $exception) {
                if ($exception->getMessage() === 'User does not exist') {
                    $response = "El usuario especificado (" . $request->query('userId') . ") no existe";
                    return response()->json(['error' => $response], 409);
                }
                return response()->json(['error' => 'Error del servidor al crear el usuario'], 500);
            }
        }

        return $this->timelineValidator->timelineResponseValidator($request);
    }
}
