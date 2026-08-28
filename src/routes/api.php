<?php

use Dennenboom\VerdantUI\Http\Controllers\VatNumberCheckController;
use Illuminate\Support\Facades\Route;

Route::post('/check-vat-number', [VatNumberCheckController::class, 'verify']);