<?php

namespace App\Http\Controllers;

use App\Services\RoutingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class RoutingController extends Controller
{
    private RoutingService $service;

    public function __construct(RoutingService $service)
    {
        $this->service = $service;
    }

    /**
     * POST /routing/route
     * Body JSON: { "coordinates": [ {"lat": -23.5, "lon": -46.6}, ... ] }
     */
    public function route(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'coordinates' => 'required|array|min:2',
            'coordinates.*.lat' => 'required|numeric',
            'coordinates.*.lon' => 'required|numeric',
        ]);

        try {
            $result = $this->service->route($validated['coordinates']);
            return response()->json($result);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Falha ao consultar OSRM', 'details' => $e->getMessage()], 502);
        }
    }

    /**
     * POST /routing/matrix
     * Body JSON: { "coordinates": [ {"lat": -23.5, "lon": -46.6}, ... ] }
     */
    public function matrix(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'coordinates' => 'required|array|min:1',
            'coordinates.*.lat' => 'required|numeric',
            'coordinates.*.lon' => 'required|numeric',
        ]);

        try {
            $result = $this->service->matrix($validated['coordinates']);
            return response()->json($result);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Falha ao consultar OSRM', 'details' => $e->getMessage()], 502);
        }
    }
}
