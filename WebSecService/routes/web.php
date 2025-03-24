<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Mail;

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

Route::get('/minitest', function () {
    // Sample bill information object
    $bill = [
        'items' => [
            ['name' => 'Milk', 'quantity' => 2, 'price' => 2.50, 'total' => 5.00],
            ['name' => 'Bread', 'quantity' => 1, 'price' => 1.20, 'total' => 1.20],
            ['name' => 'Eggs', 'quantity' => 12, 'price' => 0.20, 'total' => 2.40],
            ['name' => 'Butter', 'quantity' => 1, 'price' => 3.00, 'total' => 3.00],
        ],
        'subtotal' => 11.60,
        'tax' => 1.16, // Assuming 10% tax
        'total' => 12.76,
    ];

    return view('minitest', ['bill' => $bill]);
})->name('minitest');

Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript');

Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');

Route::resource('users', UserController::class);

Route::resource('grades', GradeController::class);

Route::resource('questions', QuestionController::class);
Route::get('/exam', [QuestionController::class, 'startExam'])->name('questions.startExam');
Route::post('/exam/submit', [QuestionController::class, 'submitExam'])->name('questions.submitExam');

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

Route::get('forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [UserController::class, 'verifySecurityQuestion'])->name('password.email');
// Route::get('verify-answer', [UserController::class, 'showVerifyAnswerForm'])->name('password.verify');
// Route::post('verify-answer', [UserController::class, 'checkSecurityAnswer'])->name('password.check');
Route::get('reset-password', [UserController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [UserController::class, 'resetPassword'])->name('password.update');

Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from WebSecService.', function ($message) {
            $message->to('mohamedamrr666@gmail.com')
                    ->subject('Test Email');
        });
        return 'Test email sent successfully!';
    } catch (\Exception $e) {
        return 'Failed to send test email: ' . $e->getMessage();
    }
});