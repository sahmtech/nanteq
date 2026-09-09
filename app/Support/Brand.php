<?php

namespace App\Support;

class Brand
{
    public static function logo(): string
    {
        return asset('images/brand/nanteq-logo.png');
    }

    public static function icon(): string
    {
        return asset('images/brand/nanteq-icon.png');
    }

    public static function favicon(): string
    {
        return asset('images/brand/favicon-32.png');
    }

    public static function appleTouchIcon(): string
    {
        return asset('images/brand/apple-touch-icon.png');
    }
}
