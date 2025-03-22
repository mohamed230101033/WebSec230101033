<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['startExam', 'submitExam']);
    }

    // View all questions
    public function index()
    {
        $questions = Question::all();
        return view('questions.index', compact('questions'));
    }

    // Show form to create a new question
    public function create()
    {
        return view('questions.create');
    }

    // Store a new question
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        Question::create($request->all());

        return redirect()->route('questions.index')->with('success', 'Question added successfully.');
    }

    // Show form to edit a question
    public function edit(Question $question)
    {
        return view('questions.edit', compact('question'));
    }

    // Update a question
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        $question->update($request->all());

        return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
    }

    // Delete a question
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Question deleted successfully.');
    }

    // Start the exam
    public function startExam()
    {
        $questions = Question::all();
        if ($questions->isEmpty()) {
            return redirect()->route('questions.index')->with('error', 'No questions available. Please add some questions first.');
        }
        return view('questions.exam', compact('questions'));
    }

    // Submit the exam and show results
    public function submitExam(Request $request)
    {
        $questions = Question::all();
        $answers = $request->input('answers', []);
        $score = 0;
        $total = $questions->count();

        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$question->id] ?? null;
            if ($userAnswer && $userAnswer === $question->correct_answer) {
                $score++;
            }
        }

        $percentage = ($score / $total) * 100;

        return view('questions.result', compact('score', 'total', 'percentage'));
    }
}