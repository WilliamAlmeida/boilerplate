<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoService
{
    public static function getIpInfo($ip): array
    {
        try {
            $response = Http::get("https://get.geojs.io/v1/ip/geo/{$ip}.json");
            if ($response->successful()) {
                return $response->json();
            }
            return [];
        } catch (\Throwable $th) {
            return [];
        }
    }
}