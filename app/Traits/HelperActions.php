<?php

namespace App\Traits;

trait HelperActions
{
    public function slideOpenJs($name, int $delay = 10)
    {
        $this->js('
            setTimeout(() => {
                $slideOpen("'.$name.'");
            }, '.$delay.');
        ');
    }

    public function slideCloseJs($name, int $delay = 10)
    {
        $this->js('
            setTimeout(() => {
                $slideClose("'.$name.'");
            }, '.$delay.');
        ');
    }
}
