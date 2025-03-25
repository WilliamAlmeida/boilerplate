<?php

namespace App\Enums;

enum EnumContratoStatus: string
{
    case DIGITAR                = 'digitar';
    case PORT_PENDENTE_ACEITE   = 'port-pendente-aceite';
    case PORT_ACEITE_FEITO      = 'port-aceite-feito';
    case PENDENTE_ASSINATURA    = 'pendente-assinatura';
    case ASSINATURA_CONCLUIDA   = 'assinatura-concluida';
    case AGUARDANDO_SD_DEVEDOR  = 'aguardando-sd-devedor';
    case SD_PAGO_DESAVERBA      = 'sd-pago-desaverba';
    case REFIN_PENDENTE_ACEITE  = 'refin-pendente-aceite';
    case REFIN_ACEITE_FEITO     = 'refin-aceite-feito';
    case EFETIVADO              = 'efetivado';
    case BENEFICIO_BLOQUEADO    = 'beneficio-bloqueado';
    case REPROVADO_VER          = 'reprovado-ver';
    case REPROVADO_100          = 'reprovado-100';

    public function label(): string
    {
        return $this->labels()[$this->value];
    }

    public static function labels(): array
    {
        return [
            self::DIGITAR->value => 'Digitar',
            self::PORT_PENDENTE_ACEITE->value => 'Port Pendente Aceite',
            self::PORT_ACEITE_FEITO->value => 'Port Aceite Feito',
            self::PENDENTE_ASSINATURA->value => 'Pendente Assinatura',
            self::ASSINATURA_CONCLUIDA->value => 'Assinatura Concluída',
            self::AGUARDANDO_SD_DEVEDOR->value => 'Aguardando SD Devedor',
            self::SD_PAGO_DESAVERBA->value => 'SD Pago/Desaverba',
            self::REFIN_PENDENTE_ACEITE->value => 'Refin Pendente Aceite',
            self::REFIN_ACEITE_FEITO->value => 'Refin Aceite Feito',
            self::EFETIVADO->value => 'Efetivado',
            self::BENEFICIO_BLOQUEADO->value => 'Benefício Bloqueado',
            self::REPROVADO_VER->value => 'Reprovado Ver',
            self::REPROVADO_100->value => 'Reprovado 100%',
        ];
    }
}

