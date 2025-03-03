<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\UserController;

Route::resource('users', UserController::class);

Route::get('/', function () {
    return view('welcome');
});
Route::get('/multable/{number?}', function ($number = 5) {
    $j = $number;
 return view('multable', compact('j')); 
});
Route::get('/even', function () {
 return view('even'); 
});
Route::get('/prime', function () {
 return view('prime');
});
Route::get('/MiniTest', function () {
    $bill =[
        [ 'item' => 'jam', 'Quantity' => 1,'Price'=> 12.50],
        [ 'item' => 'tea', 'Quantity' => 3,'Price'=> 32.00],
        [ 'item' => 'banana', 'Quantity' => 5,'Price'=> 2.20],
        [ 'item' => 'Rice', 'Quantity' => 2,'Price'=> 5.75],
    ];


    return view('MiniTest', compact('bill'));
});
Route::get('/transcript', function () {
    $student = [
        ['name'=> 'Mohamed',
        'id'=> '230101033',
        'department' => 'Cyber Security',
        'gpa' => 4.0,
        'courses' => [
            ['course' => 'Web Security', 'grade' => 'A+', 'code'=> ' CSC 101'],
            ['course' => 'Data Structures', 'grade' => 'A-', 'code'=> ' CSC 102'],
            ['course' => 'Algorithms', 'grade' => 'A', 'code'=> ' CSC 103'],
            ['course' => 'Networking', 'grade' => 'A', 'code'=> ' CSC 104'],
            ['course' => 'Operating Systems', 'grade' => 'A+', 'code'=> ' CSC 105'],
        ]
    ]
];

return view('transcript', compact('student'));
});

Route::get('/prime', function () {
    return view('prime');
   });  

