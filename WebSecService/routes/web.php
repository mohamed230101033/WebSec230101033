<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/multable/{number?}', function ($number = 5) {
    $j = $number;
 return view('multable', compact('j')); //multable.blade.php
});
Route::get('/even', function () {
 return view('even'); //even.blade.php
});
Route::get('/prime', function () {
 return view('prime'); //prime.blade.php
});
