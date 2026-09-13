<?php

if (! function_exists('highlight_file')) {
    function highlight_file($filename, $return = false)
    {
        $contents = is_readable($filename) ? (string) file_get_contents($filename) : '';
        $html = '<pre>'.htmlspecialchars($contents, ENT_QUOTES, 'UTF-8').'</pre>';

        if ($return) {
            return $html;
        }

        echo $html;

        return true;
    }
}

if (!function_exists('get_media_url')) {
    function get_media_url($media)
    {
        if (blank($media)) {
            return null;
        }

        $path = (string) $media;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $base = rtrim((string) (config('app.url') ?: env('APP_URL')), '/');

        return $base.'/storage/'.ltrim($path, '/');
    }
}

