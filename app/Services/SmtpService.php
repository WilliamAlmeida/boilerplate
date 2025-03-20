<?php

namespace App\Services;

use App\Mail\testSmtp;
use Illuminate\Support\Facades\Mail;

class SmtpService
{
    public static function testSmtp($host, $port, $username, $password, $encryption, $from_address, $from_name): bool
    {
        try {
            config([
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => $port,
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.from.address' => $from_address,
                'mail.from.name' => $from_name,
            ]);

            Mail::to($from_address)->send(new testSmtp(route('home')));

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }    
}
