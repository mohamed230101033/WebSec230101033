<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TranscriptController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth'); // Restrict to logged-in users
    // }

    public function index()
    {
        // Sample transcript data (array of courses and grades)
        $transcript = [
            ['course' => 'Web Security', 'grade' => 'A'],
            ['course' => 'Network Fundamentals', 'grade' => 'B+'],
            ['course' => 'Cyber Defense', 'grade' => 'A-'],
            ['course' => 'Database Systems', 'grade' => 'B'],
        ];

        return view('transcript', compact('transcript'));
    }
}