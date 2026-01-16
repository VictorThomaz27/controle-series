<?php

use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('home');
});
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/login', [UsersController::class, 'index']);
Route::post('/login', [UsersController::class, 'authenticate']);
Route::get('/usuario/criar', [UsersController::class, 'create']);
Route::post('/usuario/salvar', [UsersController::class, 'store']);
Route::get('/perfil', [UsersController::class, 'profile']);

// Clientes
Route::get('/clientes', [ClientsController::class, 'index']);
Route::get('/clientes/criar', [ClientsController::class, 'create']);
Route::post('/clientes/salvar', [ClientsController::class, 'store']);
Route::get('/clientes/{client}/editar', [ClientsController::class, 'edit']);
Route::put('/clientes/{client}', [ClientsController::class, 'update']);
Route::delete('/clientes/{client}', [ClientsController::class, 'destroy']);

// Motoristas
Route::get('/motoristas', [DriversController::class, 'index']);
Route::get('/motoristas/criar', [DriversController::class, 'create']);
Route::post('/motoristas/salvar', [DriversController::class, 'store']);
Route::get('/motoristas/{driver}/editar', [DriversController::class, 'edit']);
Route::put('/motoristas/{driver}', [DriversController::class, 'update']);
Route::delete('/motoristas/{driver}', [DriversController::class, 'destroy']);

// Empresas
Route::get('/empresas', [CompaniesController::class, 'index']);
Route::get('/empresas/criar', [CompaniesController::class, 'create']);
Route::post('/empresas/salvar', [CompaniesController::class, 'store']);
Route::get('/empresas/{company}/editar', [CompaniesController::class, 'edit']);
Route::put('/empresas/{company}', [CompaniesController::class, 'update']);
Route::delete('/empresas/{company}', [CompaniesController::class, 'destroy']);

// Rotas
Route::get('/rotas', [RoutesController::class, 'index']);
Route::get('/rotas/criar', [RoutesController::class, 'create']);
Route::post('/rotas/salvar', [RoutesController::class, 'store']);
Route::get('/rotas/{route}/editar', [RoutesController::class, 'edit']);
Route::put('/rotas/{route}', [RoutesController::class, 'update']);
Route::delete('/rotas/{route}', [RoutesController::class, 'destroy']);

// Contratos
Route::get('/contratos/criar', [ContractsController::class, 'create']);
Route::post('/contratos/salvar', [ContractsController::class, 'store']);

// Configurações
Route::get('/configuracoes', [SettingsController::class, 'index']);
Route::get('/series', [SeriesController::class, 'index']);
Route::get('/series/criar', [SeriesController::class, 'create']);
Route::post('/series/salvar', [SeriesController::class, 'store']);

// Página de teste de rotas (visual)
Route::get('/routing', function () {
    return view('routing.index');
});
