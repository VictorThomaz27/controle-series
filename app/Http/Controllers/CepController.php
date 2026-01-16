<?php

namespace App\Http\Controllers;

use App\Services\CepService;
use Illuminate\Http\JsonResponse;

class CepController extends Controller
{
    private CepService $service;

    public function __construct(CepService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/cep/{cep} -> retorna dados do CEP
     */
    public function show(string $cep): JsonResponse
    {
        try {
            $data = $this->service->lookup($cep);
            return response()->json($data);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Falha ao consultar CEP', 'details' => $e->getMessage()], 502);
        }
    }
}
