<?php

namespace App\Services;

use GuzzleHttp\Client;
use InvalidArgumentException;

class GeocodingService
{
    private Client $client;
    private string $baseUrl;
    private string $provider;
    private string $country;
    private string $lang;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('geocoding.base_url'), '/');
        $this->provider = strtolower(config('geocoding.provider'));
        $this->country = strtolower(config('geocoding.country'));
        $this->lang = config('geocoding.lang');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 15,
        ]);
    }

    /**
     * Geocodifica a partir de CEP ou endereço completo.
     * Retorna ['lat'=>float, 'lon'=>float, 'display_name'=>string, 'address'=>array]
     */
    public function geocode(?string $cep, ?string $addressLine, ?string $city, ?string $state): array
    {
        $query = $this->buildQuery($cep, $addressLine, $city, $state);
        if (!$query) {
            throw new InvalidArgumentException('Informe CEP ou endereço para geocodificação.');
        }

        if ($this->provider === 'nominatim') {
            return $this->geocodeNominatim($query);
        }
        if ($this->provider === 'photon') {
            return $this->geocodePhoton($query);
        }
        throw new InvalidArgumentException('Provider de geocodificação inválido.');
    }

    private function buildQuery(?string $cep, ?string $addressLine, ?string $city, ?string $state): ?string
    {
        $cep = $cep ? preg_replace('/\D+/', '', $cep) : null;
        $parts = [];
        if ($cep && strlen($cep) >= 8) {
            $parts[] = $cep;
        }
        if ($addressLine) { $parts[] = $addressLine; }
        if ($city) { $parts[] = $city; }
        if ($state) { $parts[] = $state; }
        $parts[] = 'Brasil';
        $query = trim(implode(', ', array_filter($parts)));
        return $query ?: null;
    }

    private function geocodeNominatim(string $query): array
    {
        $path = '/search';
        $params = [
            'q' => $query,
            'format' => 'json',
            'addressdetails' => 1,
            'limit' => 1,
            'accept-language' => $this->lang,
            'countrycodes' => $this->country,
        ];
        $res = $this->client->request('GET', $path, ['query' => $params]);
        $data = json_decode($res->getBody()->getContents(), true);
        if (!is_array($data) || count($data) === 0) {
            throw new InvalidArgumentException('Endereço não encontrado.');
        }
        $item = $data[0];
        return [
            'lat' => isset($item['lat']) ? (float)$item['lat'] : null,
            'lon' => isset($item['lon']) ? (float)$item['lon'] : null,
            'display_name' => $item['display_name'] ?? '',
            'address' => $item['address'] ?? [],
        ];
    }

    private function geocodePhoton(string $query): array
    {
        $path = '/api';
        $params = [
            'q' => $query,
            'lang' => $this->lang,
            'limit' => 1,
        ];
        $res = $this->client->request('GET', $path, ['query' => $params]);
        $data = json_decode($res->getBody()->getContents(), true);
        if (!isset($data['features'][0])) {
            throw new InvalidArgumentException('Endereço não encontrado.');
        }
        $feat = $data['features'][0];
        $coords = $feat['geometry']['coordinates']; // [lon, lat]
        $props = $feat['properties'] ?? [];
        return [
            'lat' => isset($coords[1]) ? (float)$coords[1] : null,
            'lon' => isset($coords[0]) ? (float)$coords[0] : null,
            'display_name' => $props['name'] ?? ($props['label'] ?? ''),
            'address' => $props,
        ];
    }
}
