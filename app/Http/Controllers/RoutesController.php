<?php

namespace App\Http\Controllers;

use App\Models\RoutePlan;
use App\Models\Client;
use App\Models\Company;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Services\GeocodingService;
use App\Services\RoutingService;

class RoutesController extends Controller
{
    public function index()
    {
        $routes = RoutePlan::latest()->paginate(10);
        return view('routes.index', compact('routes'));
    }

    public function create()
    {
        $clients = Client::orderBy('full_name')->get(['id','full_name','city','state']);
        $companies = Company::orderBy('name')->get(['id','name','city','state']);
        $drivers = Driver::orderBy('full_name')->get(['id','full_name']);
        return view('routes.create', compact('clients','companies','drivers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'origin_address' => ['required','string','max:255'],
            'destination_address' => ['required','string','max:255'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'days' => ['required','string','max:120'],
            'distance_km' => ['nullable','numeric','min:0'],
            'driver_id' => ['nullable','integer','exists:drivers,id'],
            'client_ids' => ['nullable','array'],
            'client_ids.*' => ['integer','exists:clients,id'],
            'company_ids' => ['nullable','array'],
            'company_ids.*' => ['integer','exists:companies,id'],
        ]);
        $clientIds = $data['client_ids'] ?? [];
        $companyIds = $data['company_ids'] ?? [];
        unset($data['client_ids'],$data['company_ids']);

        $route = RoutePlan::create($data);

        if (!empty($clientIds)) {
            $route->clients()->sync($clientIds);
        }
        if (!empty($companyIds)) {
            $route->companies()->sync($companyIds);
        }

        if (empty($data['distance_km'])) {
            try {
                $computed = $this->computeDistanceKm(
                    $data['origin_address'],
                    $data['destination_address'],
                    $clientIds,
                    $companyIds
                );
                if ($computed !== null) {
                    $route->update(['distance_km' => $computed]);
                }
            } catch (\Throwable $e) {
                // Silencia erros de cálculo para não bloquear o fluxo
            }
        }

        return redirect('/rotas')->with('status', 'Rota cadastrada com sucesso.');
    }

    public function edit(RoutePlan $route)
    {
        $clients = Client::orderBy('full_name')->get(['id','full_name','city','state']);
        $companies = Company::orderBy('name')->get(['id','name','city','state']);
        $drivers = Driver::orderBy('full_name')->get(['id','full_name']);
        $selectedClients = $route->clients()->pluck('clients.id')->toArray();
        $selectedCompanies = $route->companies()->pluck('companies.id')->toArray();
        return view('routes.edit', compact('route','clients','companies','drivers','selectedClients','selectedCompanies'));
    }

    public function update(Request $request, RoutePlan $route)
    {
        $data = $request->validate([
            'origin_address' => ['required','string','max:255'],
            'destination_address' => ['required','string','max:255'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'days' => ['required','string','max:120'],
            'distance_km' => ['nullable','numeric','min:0'],
            'driver_id' => ['nullable','integer','exists:drivers,id'],
            'client_ids' => ['nullable','array'],
            'client_ids.*' => ['integer','exists:clients,id'],
            'company_ids' => ['nullable','array'],
            'company_ids.*' => ['integer','exists:companies,id'],
        ]);
        $clientIds = $data['client_ids'] ?? [];
        $companyIds = $data['company_ids'] ?? [];
        unset($data['client_ids'],$data['company_ids']);

        $route->update($data);
        $route->clients()->sync($clientIds);
        $route->companies()->sync($companyIds);

        return redirect('/rotas')->with('status', 'Rota atualizada com sucesso.');
    }

    public function destroy(RoutePlan $route)
    {
        $route->delete();
        return redirect('/rotas')->with('status', 'Rota removida com sucesso.');
    }

    private function computeDistanceKm(string $origin, string $destination, array $clientIds, array $companyIds): ?float
    {
        $geo = new GeocodingService();
        $osrm = new RoutingService();

        $coords = [];
        // origem
        $o = $geo->geocode(null, $origin, null, null);
        if (!empty($o['lat']) && !empty($o['lon'])) {
            $coords[] = ['lat' => (float)$o['lat'], 'lon' => (float)$o['lon']];
        }

        // clientes
        if (!empty($clientIds)) {
            $clients = Client::whereIn('id', $clientIds)->get();
            foreach ($clients as $c) {
                $lat = $c->lat; $lon = $c->lon;
                if (!$lat || !$lon) {
                    $line = trim(($c->address_line ?? '') . (isset($c->address_number) && $c->address_number ? ', ' . $c->address_number : ''));
                    try {
                        $gc = $geo->geocode($c->zip_code, $line ?: null, $c->city, $c->state);
                        $lat = $gc['lat'] ?? null; $lon = $gc['lon'] ?? null;
                    } catch (\Throwable $e) {}
                }
                if ($lat && $lon) { $coords[] = ['lat' => (float)$lat, 'lon' => (float)$lon]; }
            }
        }

        // empresas
        if (!empty($companyIds)) {
            $companies = Company::whereIn('id', $companyIds)->get();
            foreach ($companies as $e) {
                $lat = $e->lat; $lon = $e->lon;
                if (!$lat || !$lon) {
                    $line = trim(($e->address_line ?? '') . (isset($e->address_number) && $e->address_number ? ', ' . $e->address_number : ''));
                    try {
                        $gc = $geo->geocode($e->zip_code, $line ?: null, $e->city, $e->state);
                        $lat = $gc['lat'] ?? null; $lon = $gc['lon'] ?? null;
                    } catch (\Throwable $ex) {}
                }
                if ($lat && $lon) { $coords[] = ['lat' => (float)$lat, 'lon' => (float)$lon]; }
            }
        }

        // destino
        $d = $geo->geocode(null, $destination, null, null);
        if (!empty($d['lat']) && !empty($d['lon'])) {
            $coords[] = ['lat' => (float)$d['lat'], 'lon' => (float)$d['lon']];
        }

        if (count($coords) < 2) {
            return null;
        }

        $res = $osrm->route($coords);
        if (!isset($res['routes'][0]['distance'])) { return null; }
        $meters = (float)$res['routes'][0]['distance'];
        $km = $meters / 1000.0;
        return round($km, 1);
    }

}
