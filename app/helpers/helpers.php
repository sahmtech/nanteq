<?php

if (!function_exists('get_media_url')) {
    function get_media_url($media)
    {
        return env('APP_URL') . '/storage/' . $media;
    }
}

