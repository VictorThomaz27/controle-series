<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\ClientsApiController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\CepController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// OSRM Routing endpoints
Route::post('/routing/route', [RoutingController::class, 'route']);
Route::post('/routing/matrix', [RoutingController::class, 'matrix']);

// Clientes com coordenadas
Route::get('/clients/coords', [ClientsApiController::class, 'withCoords']);
Route::post('/clients/geocode-coords', [ClientsApiController::class, 'geocodeCoords']);

// Geocoding (CEP/Endereço -> lat/lon)
Route::post('/geocode', [GeocodingController::class, 'geocode']);

// CEP -> endereço (ViaCEP)
Route::get('/cep/{cep}', [CepController::class, 'show']);
