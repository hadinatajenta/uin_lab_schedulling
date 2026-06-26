<?php

if (!function_exists('format_angka')) {
    /**
     * Format a number with Indonesian locale style (thousands separated by dot).
     *
     * @param mixed $value
     * @return string
     */
    function format_angka($value)
    {
        if (is_null($value) || !is_numeric($value)) {
            return '0';
        }

        return number_format($value, 0, ',', '.');
    }
}
