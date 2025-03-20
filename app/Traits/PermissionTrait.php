<?php

namespace App\Traits;

trait PermissionTrait
{
    private function permissions(array $default = ['view', 'create', 'edit', 'delete', 'forceDelete'], array $extra = []): object
    {
        $user = auth()->user();

        foreach($default as $value) {
            $response[$value] = $user->can($this->resource.'.'.$value);
        }

        foreach($extra as $key => $value) {
            $response[$key] = $user->can($value);
        }

        return (object) $response;
    }
}