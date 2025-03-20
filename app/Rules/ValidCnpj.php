<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove non-alphanumeric characters (keeping letters and numbers)
        $cnpj = preg_replace('/[^0-9A-Z]/', '', strtoupper($value));

        // Validate length
        if (strlen($cnpj) != 14) {
            $fail('O :attribute deve conter 14 caracteres.');
            return;
        }

        // Check if it's the current numeric format or the new alfanumeric format
        $isNumeric = preg_match('/^[0-9]{14}$/', $cnpj);
        $isAlfanumeric = preg_match('/^[0-9A-Z]{12}[0-9]{2}$/', $cnpj);

        if (!$isNumeric && !$isAlfanumeric) {
            $fail('O formato do :attribute é inválido.');
            return;
        }

        // For numeric CNPJ, check for known invalid patterns
        if ($isNumeric && preg_match('/^(\d)\1{13}$/', $cnpj)) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Validate check digits based on format
        if ($isNumeric) {
            $this->validateNumericCnpj($cnpj, $attribute, $fail);
        } else {
            $this->validateAlfanumericCnpj($cnpj, $attribute, $fail);
        }
    }

    /**
     * Validate a numeric CNPJ (current format)
     */
    private function validateNumericCnpj(string $cnpj, string $attribute, Closure $fail): void
    {
        // Validate first check digit
        $sum = 0;
        $multipliers = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpj[$i] * $multipliers[$i];
        }
        
        $remainder = $sum % 11;
        $checkDigit1 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if ($checkDigit1 != (int) $cnpj[12]) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Validate second check digit
        $sum = 0;
        $multipliers = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpj[$i] * $multipliers[$i];
        }
        
        $remainder = $sum % 11;
        $checkDigit2 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if ($checkDigit2 != (int) $cnpj[13]) {
            $fail('O :attribute informado é inválido.');
            return;
        }
    }

    /**
     * Validate an alfanumeric CNPJ (future format from July 2026)
     */
    private function validateAlfanumericCnpj(string $cnpj, string $attribute, Closure $fail): void
    {
        // Validate first check digit
        $sum = 0;
        $multipliers = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        
        for ($i = 0; $i < 12; $i++) {
            // Convert character to its ASCII value and subtract 48 as per RFB specification
            $charValue = $this->getAdjustedAsciiValue($cnpj[$i]);
            $sum += $charValue * $multipliers[$i];
        }
        
        $remainder = $sum % 11;
        $checkDigit1 = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($checkDigit1 != (int) $cnpj[12]) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Validate second check digit
        $sum = 0;
        $multipliers = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        
        for ($i = 0; $i < 13; $i++) {
            // For the 13th position, use the numeric value directly (it's the first check digit)
            $charValue = ($i < 12) ? $this->getAdjustedAsciiValue($cnpj[$i]) : (int) $cnpj[$i];
            $sum += $charValue * $multipliers[$i];
        }
        
        $remainder = $sum % 11;
        $checkDigit2 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if ($checkDigit2 != (int) $cnpj[13]) {
            $fail('O :attribute informado é inválido.');
            return;
        }
    }

    /**
     * Get the adjusted ASCII value as per RFB specification
     * For digits 0-9: use their numeric value
     * For letters A-Z: use ASCII value - 48 (A=17, B=18, etc.)
     */
    private function getAdjustedAsciiValue(string $char): int
    {
        if (is_numeric($char)) {
            return (int) $char;
        } else {
            // ASCII value of the character minus 48
            return ord($char) - 48; 
        }
    }
}
