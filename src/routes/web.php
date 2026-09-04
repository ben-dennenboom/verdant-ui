<?php

use Dennenboom\VerdantUI\Http\Controllers\VatNumberCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/verdant/vat-number-check', [VatNumberCheckController::class, 'verify'])
    ->middleware('throttle:30,1')
    ->name('verdant.vat-number-check');
