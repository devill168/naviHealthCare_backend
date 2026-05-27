<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;


Route::post('/register', [RegisterController::class, 'store'])->name('store');

Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');

