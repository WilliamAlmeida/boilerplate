<?php

namespace App\Models;

use App\Casts\DatetimeWithTimezoneGetOnly;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $fillable = ['name', 'token', 'abilities', 'last_used_at', 'expires_at', 'token_secret'];

    protected function casts() : array
    {
        return [
            'created_at' => DatetimeWithTimezoneGetOnly::class,
            'updated_at' => DatetimeWithTimezoneGetOnly::class,
            'last_used_at' => DatetimeWithTimezoneGetOnly::class,
            'expires_at' => DatetimeWithTimezoneGetOnly::class,
        ];
    }

    static public $list_expire_at = [
        [
            'id' => 1,
            'name' => '24 hora',
            'days' => 1,
        ],
        [
            'id' => 2,
            'name' => '7 dias',
            'days' => 7,
        ],
        [
            'id' => 3,
            'name' => '30 dias',
            'days' => 30,
        ],
        [
            'id' => 4,
            'name' => '60 dias',
            'days' => 60,
        ],
        [
            'id' => 5,
            'name' => '90 dias',
            'days' => 90,
        ],
        [
            'id' => 6,
            'name' => '1 ano',
            'days' => 365,
        ],
        [
            'id' => 7,
            'name' => 'Sem expiração',
            'days' => null,
        ],
    ];
}