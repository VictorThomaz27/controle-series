<?php

namespace App\Services;

use GuzzleHttp\Client;
use InvalidArgumentException;

class CepService
{
    private Client $client;
    private string $baseUrl;

    public function __construct()
    {
        // ViaCEP público; pode ser alterado via env se desejar proxy
        $this->baseUrl = rtrim(env('CEPCODE_BASE_URL', 'https://viacep.com.br'), '/');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 10,
        ]);
    }

    /**
     * Consulta CEP no ViaCEP.
     * Retorna array com chaves: cep, logradouro, bairro, localidade, uf.
     */
    public function lookup(string $cep): array
    {
        $cep = preg_replace('/\D+/', '', $cep);
        if (strlen($cep) < 8) {
            throw new InvalidArgumentException('CEP inválido.');
        }
        $path = "/ws/{$cep}/json/";
        $res = $this->client->request('GET', $path);
        $data = json_decode($res->getBody()->getContents(), true);
        if (!is_array($data) || isset($data['erro'])) {
            throw new InvalidArgumentException('CEP não encontrado.');
        }
        return [
            'cep' => $data['cep'] ?? $cep,
            'logradouro' => $data['logradouro'] ?? '',
            'bairro' => $data['bairro'] ?? '',
            'localidade' => $data['localidade'] ?? '',
            'uf' => $data['uf'] ?? '',
        ];
    }
}
