<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;

class ClientsApiController extends Controller
{
    /**
     * GET /api/clients/coords
     * Retorna clientes com lat/lon definidos
     */
    public function withCoords(): JsonResponse
    {
        $clients = Client::orderBy('full_name')
            ->get(['id','full_name','lat','lon','address_line','address_number','city','state','zip_code']);

        return response()->json($clients);
    }

    /**
     * POST /api/clients/geocode-coords
     * Body: { ids: [1,2,...] }
     * Geocodifica clientes sem lat/lon e salva no banco, retorna lista com id, lat, lon.
     */
    public function geocodeCoords(\Illuminate\Http\Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids' => ['required','array','min:1'],
            'ids.*' => ['integer','exists:clients,id'],
        ]);

        $svc = new \App\Services\GeocodingService();
        $results = [];
        $clients = Client::whereIn('id', $data['ids'])->get();
        foreach ($clients as $c) {
            if (!empty($c->lat) && !empty($c->lon)) {
                $results[] = ['id' => $c->id, 'lat' => (float)$c->lat, 'lon' => (float)$c->lon];
                continue;
            }
            try {
                $line = $c->address_line ?? '';
                if (!empty($c->address_number)) {
                    $line = trim($line . ', ' . $c->address_number);
                }
                $geo = $svc->geocode($c->zip_code ?? null, $line ?: null, $c->city ?? null, $c->state ?? null);
                if (!empty($geo['lat']) && !empty($geo['lon'])) {
                    $c->lat = $geo['lat'];
                    $c->lon = $geo['lon'];
                    $c->save();
                    $results[] = ['id' => $c->id, 'lat' => (float)$c->lat, 'lon' => (float)$c->lon];
                }
            } catch (\Throwable $e) {
                // ignora falhas individuais
            }
        }

        return response()->json($results);
    }
}
