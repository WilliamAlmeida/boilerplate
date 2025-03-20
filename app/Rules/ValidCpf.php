<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove any non-numeric characters
        $cpf = preg_replace('/[^0-9]/', '', $value);

        // Validate length
        if (strlen($cpf) != 11) {
            $fail('O :attribute deve conter 11 dígitos.');
            return;
        }

        // Check for known invalid CPF patterns
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Validate first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $cpf[$i] * (10 - $i);
        }
        
        $remainder = $sum % 11;
        $checkDigit1 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if ($checkDigit1 != (int) $cpf[9]) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Validate second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $cpf[$i] * (11 - $i);
        }
        
        $remainder = $sum % 11;
        $checkDigit2 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if ($checkDigit2 != (int) $cpf[10]) {
            $fail('O :attribute informado é inválido.');
            return;
        }
    }
}
