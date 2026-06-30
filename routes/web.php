<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;

Route::get('/{code}', [LinkController::class, 'redirect'])->name('link.redirect');
