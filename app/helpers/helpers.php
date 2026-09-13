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
        return env('APP_URL') . '/storage/' . $media;
    }
}

