<?php

namespace App\Helpers;

class Helper
{
    public static function updatePageConfig($pageConfigs)
    {
        if (!$pageConfigs) {
            return '';
        }

        foreach ($pageConfigs as $key => $value) {
            config([$key => $value]);
        }

        return '';
    }

    /**
     * Format currency to Hungarian standard (e.g., 3 000 Ft)
     * 
     * @param mixed $amount
     * @param bool $perMonth
     * @return string
     */
    public static function formatCurrency($amount, $perMonth = false)
    {
        $formatted = number_format($amount ?? 0, 0, ',', ' ') . ' Ft';
        return $perMonth ? $formatted . ' / hó' : $formatted;
    }
}