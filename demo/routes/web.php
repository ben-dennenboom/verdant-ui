<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Hello from Verdant UI Demo!';
});

Route::get('/demo', function () {
    return view('demo');
});

Route::get('{any}', function () {
    return view('demo');
})->where('any', '.*');
