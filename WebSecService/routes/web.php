<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\CalculatorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('multable/{id?}', function ($id = 1) {
    return view('multable', [
        'number' => $id,
    ]);
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript');

Route::get('test/{id}', function ($id) {
    $courses = [
        '1' => 'englach',
        '2' => 'oop',
        '3' => 'ara'
    ];
    return view('test', [
        'this_id' => $courses[$id] ?? "does not exist",
    ]);
});

Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');

Route::resource('users', UserController::class);

Route::resource('grades', GradeController::class);

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'doRegister'])->name('doRegister');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'doLogin'])->name('doLogin');
Route::get('logout', [UserController::class, 'doLogout'])->name('doLogout');
Route::get('profile/{user?}', [UserController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('profile/update-password/{user?}', [UserController::class, 'updatePassword'])->name('updatePassword')->middleware('auth');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('auth');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');