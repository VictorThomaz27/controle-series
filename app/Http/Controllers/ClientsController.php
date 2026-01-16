<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\GeocodingService;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'cpf' => ['required','string','max:20','unique:clients,cpf'],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address_line' => ['nullable','string','max:255'],
            'address_number' => ['nullable','string','max:20'],
            'city' => ['nullable','string','max:120'],
            'state' => ['nullable','string','max:2'],
            'zip_code' => ['nullable','string','max:20'],
            'lat' => ['nullable','numeric','between:-90,90'],
            'lon' => ['nullable','numeric','between:-180,180'],
        ]);
        // Auto-geocode se lat/lon ausentes e endereço completo
        if ((!isset($data['lat']) || !$data['lat']) && (!isset($data['lon']) || !$data['lon'])) {
            $hasAddr = !empty($data['address_line']) && !empty($data['city']) && !empty($data['state']);
            $hasCep = !empty($data['zip_code']);
            if ($hasAddr || $hasCep) {
                try {
                    $svc = new GeocodingService();
                    $line = $data['address_line'] ?? '';
                    if (!empty($data['address_number'])) {
                        $line = trim($line . ', ' . $data['address_number']);
                    }
                    $res = $svc->geocode($data['zip_code'] ?? null, $line ?: null, $data['city'] ?? null, $data['state'] ?? null);
                    if (!empty($res['lat']) && !empty($res['lon'])) {
                        $data['lat'] = $res['lat'];
                        $data['lon'] = $res['lon'];
                    }
                } catch (\Throwable $e) {
                    // silencioso: não bloquear cadastro por falha externa
                }
            }
        }

        Client::create($data);

        return redirect('/clientes')->with('status', 'Cliente cadastrado com sucesso.');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'cpf' => ['required','string','max:20','unique:clients,cpf,'.$client->id],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address_line' => ['nullable','string','max:255'],
            'address_number' => ['nullable','string','max:20'],
            'city' => ['nullable','string','max:120'],
            'state' => ['nullable','string','max:2'],
            'zip_code' => ['nullable','string','max:20'],
            'lat' => ['nullable','numeric','between:-90,90'],
            'lon' => ['nullable','numeric','between:-180,180'],
        ]);
        // Auto-geocode se lat/lon ausentes e endereço completo
        if ((!isset($data['lat']) || !$data['lat']) && (!isset($data['lon']) || !$data['lon'])) {
            $hasAddr = !empty($data['address_line']) && !empty($data['city']) && !empty($data['state']);
            $hasCep = !empty($data['zip_code']);
            if ($hasAddr || $hasCep) {
                try {
                    $svc = new GeocodingService();
                    $line = $data['address_line'] ?? '';
                    if (!empty($data['address_number'])) {
                        $line = trim($line . ', ' . $data['address_number']);
                    }
                    $res = $svc->geocode($data['zip_code'] ?? null, $line ?: null, $data['city'] ?? null, $data['state'] ?? null);
                    if (!empty($res['lat']) && !empty($res['lon'])) {
                        $data['lat'] = $res['lat'];
                        $data['lon'] = $res['lon'];
                    }
                } catch (\Throwable $e) {
                    // silencioso
                }
            }
        }

        $client->update($data);

        return redirect('/clientes')->with('status', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect('/clientes')->with('status', 'Cliente removido com sucesso.');
    }
}
