<?php

return [
    // Provider: 'nominatim' ou 'photon'
    'provider' => env('GEOCODE_PROVIDER', 'nominatim'),

    // Base URL do serviço (self-host recomendado)
    // Exemplo Nominatim: http://localhost:7070
    // Exemplo Photon: http://localhost:2322
    'base_url' => env('GEOCODE_BASE_URL', 'http://localhost:7070'),

    // Código de país (para priorização de resultados)
    'country' => env('GEOCODE_COUNTRY', 'br'),

    // Idioma
    'lang' => env('GEOCODE_LANG', 'pt-BR'),
];
