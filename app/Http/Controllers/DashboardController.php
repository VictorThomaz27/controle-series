<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Driver;
use App\Models\RoutePlan;

class DashboardController extends Controller
{
    public function index()
    {
        $activeClients = Contract::where('type', 'client')
            ->where('status', 'active')
            ->whereNotNull('client_id')
            ->distinct()
            ->count('client_id');

        $routesCount = RoutePlan::count();
        $driversCount = Driver::count();

        return view('dashboard.index', compact('activeClients', 'routesCount', 'driversCount'));
    }
}
