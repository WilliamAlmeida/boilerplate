<?php

namespace App\Enums;

enum EnumTipoCliente: string
{
    case JURIDICO = 'Jurídico';
    case FISICO = 'Físico';

    public static function labels(): array
    {
        return [
            self::JURIDICO->value => 'JURÍDICO',
            self::FISICO->value => 'FÍSICO',
        ];
    }
}
