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
}