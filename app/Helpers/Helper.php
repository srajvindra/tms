<?php

namespace App\Helpers;

class Helper
{
    public static function pd(mixed ...$values): never
    {
        foreach ($values as $value) {
            echo '<pre>';
            print_r($value);
            echo '</pre>';
        }

        exit(1);
    }

    public static function pr(mixed ...$values): void
    {
        foreach ($values as $value) {
            echo '<pre>';
            print_r($value);
            echo '</pre>';
        }
    }
}
