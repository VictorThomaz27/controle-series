<?php

namespace App\Http\Controllers;

use App\Services\GeocodingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class GeocodingController extends Controller
{
    private GeocodingService $service;

    public function __construct(GeocodingService $service)
    {
        $this->service = $service;
    }

    /**
     * POST /api/geocode
     * Body: { cep?: string, address_line?: string, city?: string, state?: string }
     */
    public function geocode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cep' => ['nullable','string','max:20'],
            'address_line' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:120'],
            'state' => ['nullable','string','max:2'],
        ]);

        try {
            $result = $this->service->geocode(
                $validated['cep'] ?? null,
                $validated['address_line'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null
            );
            return response()->json($result);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Falha ao geocodificar', 'details' => $e->getMessage()], 502);
        }
    }
}
