<?php

namespace App\Traits;

use DateTimeZone;

trait HelperFunctions
{
    public function getTimezones($country = 'BR')
    {
        return cache()->remember("timezones:{$this->country}", 86400, function () use ($country) {
            return collect(DateTimeZone::listIdentifiers(
                timezoneGroup: DateTimeZone::PER_COUNTRY,
                countryCode: $country
            ))->map(function ($item) {
                return [
                    'id' => $item,
                    'name' => $item,
                ];
            })->toArray();
        });
    }
}
