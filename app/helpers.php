<?php

if (!function_exists('currency_format')) {
    function currency_format($value)
    {
        $hasil_rupiah = "Rp " . number_format($value,2,',','.');
        return $hasil_rupiah;
    }
}
