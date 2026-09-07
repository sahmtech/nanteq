<?php

use App\Models\Sound;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin');
});

Route::get('/audio/{id}', function ($id) {
    $audio = Sound::firstWhere('id', $id)->audio; 
    return Storage::disk('local')->response($audio);
})->middleware('auth');

Route::get('/xray-video/{id}', function ($id) {
    $video = Sound::firstWhere('id', $id)->xray_videos; 
    return Storage::disk('local')->response($video);
})->middleware('auth');

Route::get('/natural-video/{id}', function ($id) {
    $video = Sound::firstWhere('id', $id)->natural_videos; 
    return Storage::disk('local')->response($video);
})->middleware('auth');
