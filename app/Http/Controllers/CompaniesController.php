<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\GeocodingService;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(10);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
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

        if ((!$data['lat'] ?? true) || (!$data['lon'] ?? true)) {
            $hasAddr = !empty($data['address_line']) && !empty($data['city']) && !empty($data['state']);
            $hasCep = !empty($data['zip_code']);
            if ($hasAddr || $hasCep) {
                try {
                    $svc = new GeocodingService();
                    $line = $data['address_line'] ?? '';
                    if (!empty($data['address_number'])) { $line = trim($line . ', ' . $data['address_number']); }
                    $res = $svc->geocode($data['zip_code'] ?? null, $line ?: null, $data['city'] ?? null, $data['state'] ?? null);
                    if (!empty($res['lat']) && !empty($res['lon'])) {
                        $data['lat'] = $res['lat'];
                        $data['lon'] = $res['lon'];
                    }
                } catch (\Throwable $e) {}
            }
        }

        Company::create($data);
        return redirect('/empresas')->with('status', 'Empresa cadastrada com sucesso.');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
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

        if ((!$data['lat'] ?? true) || (!$data['lon'] ?? true)) {
            $hasAddr = !empty($data['address_line']) && !empty($data['city']) && !empty($data['state']);
            $hasCep = !empty($data['zip_code']);
            if ($hasAddr || $hasCep) {
                try {
                    $svc = new GeocodingService();
                    $line = $data['address_line'] ?? '';
                    if (!empty($data['address_number'])) { $line = trim($line . ', ' . $data['address_number']); }
                    $res = $svc->geocode($data['zip_code'] ?? null, $line ?: null, $data['city'] ?? null, $data['state'] ?? null);
                    if (!empty($res['lat']) && !empty($res['lon'])) {
                        $data['lat'] = $res['lat'];
                        $data['lon'] = $res['lon'];
                    }
                } catch (\Throwable $e) {}
            }
        }

        $company->update($data);
        return redirect('/empresas')->with('status', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect('/empresas')->with('status', 'Empresa removida com sucesso.');
    }
}
