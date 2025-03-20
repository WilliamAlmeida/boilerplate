<?php

/**
 * Apply a mask to a string.
 *
 * This function applies a given mask to a string. The mask should contain
 * placeholders (%) that will be replaced by the characters of the string.
 * If the number of placeholders in the mask does not match the number of
 * characters in the string, the function will return null.
 *
 * @param string $mask The mask to apply.
 * @param string $string The string to be masked.
 * @return string|null The masked string or null if the mask and string lengths do not match.
 */
if (! function_exists('mask')) {
    function mask($mask, $string)
    {
        if (substr_count($mask, '#') !== strlen($string)) return null;
        return vsprintf(str_replace('#', '%s', $mask), str_split($string));
	}
}