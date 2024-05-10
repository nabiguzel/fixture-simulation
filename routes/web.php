<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/{PathMatch}', function () {
    return view('home');
})->where('pathMatch', '.*');
