<?php

namespace App\Casts;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DatetimeWithTimezone implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $date = Carbon::make($value);

        if(auth()->check() && $value && auth()->user() instanceof \App\Models\User) {
            $date->setTimezone(auth()->user()->timezone);
        }

        return $date;;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if(auth()->check() && $value && auth()->user() instanceof \App\Models\User) {
            return Carbon::parse($value, auth()->user()->timezone)->setTimezone(config('app.timezone'));
        }

        return $value;
    }
}
