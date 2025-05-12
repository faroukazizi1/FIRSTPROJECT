<?php

namespace App\Service;

use OpenCage\Geocoder\Geocoder;

class GeocodingService {

    private $geocoder;

    public function __construct($openCageApiKey) {
        // Assure-toi de mettre la clé API entre guillemets
        $this->geocoder = new Geocoder($openCageApiKey);
    }

    public function geocode(string $address): ?array 
    {
        $result = $this->geocoder->geocode($address);

        if ($result && $result['total_results'] > 0) {
            $first = $result['results'][0];
            return [
                'lat' => $first['geometry']['lat'],
                'lng' => $first['geometry']['lng'],
            ];
        }

        return null;
    }
}