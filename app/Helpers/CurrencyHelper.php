<?php

if (!function_exists('formatRupiah')) {
    /**
     * Format number to Indonesian Rupiah
     *
     * @param float $amount
     * @return string
     */
    function formatRupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
