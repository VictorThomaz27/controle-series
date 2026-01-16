<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Driver;
use App\Models\RoutePlan;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
    public function create()
    {
        $clients = Client::orderBy('full_name')->get();
        $drivers = Driver::orderBy('full_name')->get();
        $routes = RoutePlan::orderBy('id','desc')->get();
        return view('contracts.create', compact('clients','drivers','routes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required','in:client,driver'],
            'client_id' => ['nullable','exists:clients,id'],
            'driver_id' => ['nullable','exists:drivers,id'],
            'route_id' => ['nullable','exists:routes,id'],
            'monthly_value' => ['required','numeric','min:0'],
            'tolerance_minutes' => ['required','integer','min:0'],
            'vacation_policy' => ['boolean'],
            'anticorruption_acceptance' => ['boolean'],
            'payer_name' => ['nullable','string','max:255'],
            'payer_cnpj' => ['nullable','string','max:20'],
            'payer_address' => ['nullable','string','max:255'],
            'start_date' => ['nullable','date'],
            'content' => ['nullable','string'],
        ]);

        // Ensure proper linking according to type
        if ($data['type'] === 'client') {
            $data['driver_id'] = $data['driver_id'] ?? null; // optional driver association
            if (empty($data['client_id'])) {
                return back()->withErrors(['client_id' => 'Selecione o cliente.'])->withInput();
            }
        } else {
            $data['client_id'] = $data['client_id'] ?? null; // optional client association
            if (empty($data['driver_id'])) {
                return back()->withErrors(['driver_id' => 'Selecione o motorista.'])->withInput();
            }
        }

        $data['vacation_policy'] = (bool)($data['vacation_policy'] ?? false);
        $data['anticorruption_acceptance'] = (bool)($data['anticorruption_acceptance'] ?? false);

        Contract::create($data);

        return redirect('/home')->with('status', 'Contrato criado com sucesso.');
    }
}
