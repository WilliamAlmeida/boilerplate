<?php

namespace App\Enums;

enum EnumSimNao: string
{
    case SIM = 's';
    case NAO = 'n';

    public static function labels(): array
    {
        return [
            self::SIM->value => 'Sim',
            self::NAO->value => 'Não',
        ];
    }
}
