<?php

if (!function_exists('indian_number_format')) {
    function indian_number_format($number)
    {
        $exploded_number = explode('.', $number);
        $whole_number = $exploded_number[0];
        $decimal = isset($exploded_number[1]) ? '.' . $exploded_number[1] : '';

        $whole_number = preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $whole_number);
        $whole_number = preg_replace('/(\d+)(\d{2})(\d{3}),/', '$1,$2,$3,', $whole_number);
        $whole_number = rtrim($whole_number, ',');

        return $whole_number . $decimal;
    }
}
