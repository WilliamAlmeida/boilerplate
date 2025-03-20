<?php

namespace App\Casts;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TimeWithTimezone implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $time = Carbon::createFromFormat('H:i:s', $value);

        if(auth()->check() && $value && auth()->user() instanceof \App\Models\User) {
            $time->setTimezone(auth()->user()->timezone);
        }

        return $time->format('H:i:s');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if(auth()->check() && $value && auth()->user() instanceof \App\Models\User) {
            return Carbon::createFromFormat('H:i:s', $value, auth()->user()->timezone)->setTimezone(config('app.timezone'))->format('H:i:s');
        }

        return $value;
    }
}