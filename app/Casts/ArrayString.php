<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ArrayString implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (is_null($value)) {
            return [];
        }
        
        // Remove the curly braces and explode the string by comma
        $value = trim($value, '{}');

        if (empty($value)) {
            return [];
        }
        
        return explode(',', $value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }
        
        if (is_array($value) && empty($value)) {
            return null;
        }
        
        if (is_string($value)) {
            $value = [$value];
        }
        
        // Format as {value,value}
        return '{' . implode(',', $value) . '}';
    }
}