<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    public function index()
    {
        $drivers = Driver::latest()->paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'cpf_cnpj' => ['required','string','max:20','unique:drivers,cpf_cnpj'],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'vehicle_model' => ['nullable','string','max:120'],
            'vehicle_plate' => ['nullable','string','max:10'],
            'vehicle_capacity' => ['nullable','integer','min:1'],
        ]);

        Driver::create($data);

        return redirect('/motoristas')->with('status', 'Motorista cadastrado com sucesso.');
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'cpf_cnpj' => ['required','string','max:20','unique:drivers,cpf_cnpj,'.$driver->id],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'vehicle_model' => ['nullable','string','max:120'],
            'vehicle_plate' => ['nullable','string','max:10'],
            'vehicle_capacity' => ['nullable','integer','min:1'],
        ]);

        $driver->update($data);

        return redirect('/motoristas')->with('status', 'Motorista atualizado com sucesso.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect('/motoristas')->with('status', 'Motorista removido com sucesso.');
    }
}
