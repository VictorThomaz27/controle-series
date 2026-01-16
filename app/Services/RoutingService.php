<?php

namespace App\Services;

use GuzzleHttp\Client;
use InvalidArgumentException;

class RoutingService
{
    private Client $client;
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('osrm.base_url'), '/');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 15,
        ]);
    }

    /**
     * Calcula rota com OSRM /route
     * @param array $coordinates Array de pontos [ ['lat'=>..., 'lon'=>...], ... ]
     */
    public function route(array $coordinates): array
    {
        if (count($coordinates) < 2) {
            throw new InvalidArgumentException('Pelo menos 2 coordenadas são necessárias para calcular a rota.');
        }

        $coordStr = $this->formatCoordinates($coordinates);
        $path = "/route/v1/driving/{$coordStr}";

        $query = [
            'overview' => 'full',
            'geometries' => 'geojson',
            'annotations' => 'distance,duration',
            'steps' => 'false',
        ];

        $response = $this->client->request('GET', $path, ['query' => $query]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Calcula matriz de distância/tempo com OSRM /table
     * @param array $coordinates Array de pontos [ ['lat'=>..., 'lon'=>...], ... ]
     */
    public function matrix(array $coordinates): array
    {
        if (count($coordinates) < 1) {
            throw new InvalidArgumentException('Ao menos 1 coordenada é necessária para a matriz.');
        }

        $coordStr = $this->formatCoordinates($coordinates);
        $path = "/table/v1/driving/{$coordStr}";
        $query = [
            'annotations' => 'distance,duration',
        ];

        $response = $this->client->request('GET', $path, ['query' => $query]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Formata coordenadas para "lon,lat;lon,lat"
     */
    private function formatCoordinates(array $coordinates): string
    {
        $parts = [];
        foreach ($coordinates as $point) {
            $lat = null;
            $lon = null;

            if (is_array($point)) {
                if (array_key_exists('lat', $point) && array_key_exists('lon', $point)) {
                    $lat = (float) $point['lat'];
                    $lon = (float) $point['lon'];
                } elseif (count($point) >= 2) {
                    // array indexado: [lat, lon] ou [lon, lat]; assumimos [lat, lon]
                    $lat = (float) $point[0];
                    $lon = (float) $point[1];
                }
            }

            if ($lat === null || $lon === null) {
                throw new InvalidArgumentException('Coordenada inválida. Use {lat, lon} ou [lat, lon].');
            }

            // OSRM exige ordem lon,lat
            $parts[] = $lon . ',' . $lat;
        }

        return implode(';', $parts);
    }
}
