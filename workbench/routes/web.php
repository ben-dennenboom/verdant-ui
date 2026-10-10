<?php

use Illuminate\Support\Facades\Route;

if (env('VERDANT_DOCS_ENABLED', false)) {
    Route::view('/', 'docs')->name('verdant.docs');
} else {
    Route::view('/', 'playground')->name('verdant.playground');
}

Route::view('/playground', 'playground');
