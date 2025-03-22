@extends('layouts.master')

@section('title', 'Start Exam')

@section('content')
    <div class="container mt-5">
        <h1>MCQ Exam</h1>

        <form action="{{ route('questions.submitExam') }}" method="POST">
            @csrf
            @foreach ($questions as $index => $question)
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5>Question {{ $index + 1 }}: {{ $question->question }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option_a_{{ $question->id }}" value="A" required>
                            <label class="form-check-label" for="option_a_{{ $question->id }}">A. {{ $question->option_a }}</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option_b_{{ $question->id }}" value="B">
                            <label class="form-check-label" for="option_b_{{ $question->id }}">B. {{ $question->option_b }}</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option_c_{{ $question->id }}" value="C">
                            <label class="form-check-label" for="option_c_{{ $question->id }}">C. {{ $question->option_c }}</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option_d_{{ $question->id }}" value="D">
                            <label class="form-check-label" for="option_d_{{ $question->id }}">D. {{ $question->option_d }}</label>
                        </div>
                    </div>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Submit Exam</button>
        </form>
    </div>

    <style>
        .form-check-input {
            display: inline-block !important;
            visibility: visible !important;
            width: 1.25rem !important;
            height: 1.25rem !important;
            margin-right: 0.5rem;
        }
        .form-check-label {
            vertical-align: middle;
        }
    </style>
@endsection