@extends('layouts.master')

@section('title', 'Start Exam')

@section('content')
    <div class="container mt-5">
        <h1>MCQ Exam</h1>

        <form action="{{ route('questions.submitExam') }}" method="POST">
            @csrf
            @forelse ($questions as $index => $question)
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5>Question {{ $index + 1 }}: {{ $question->question }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input custom-radio" type="radio" name="answers[{{ $question->id }}]" id="option_a_{{ $question->id }}" value="A" required>
                            <label class="form-check-label" for="option_a_{{ $question->id }}">A. {{ $question->option_a }}</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input custom-radio" type="radio" name="answers[{{ $question->id }}]" id="option_b_{{ $question->id }}" value="B">
                            <label class="form-check-label" for="option_b_{{ $question->id }}">B. {{ $question->option_b }}</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input custom-radio" type="radio" name="answers[{{ $question->id }}]" id="option_c_{{ $question->id }}" value="C">
                            <label class="form-check-label" for="option_c_{{ $question->id }}">C. {{ $question->option_c }}</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input custom-radio" type="radio" name="answers[{{ $question->id }}]" id="option_d_{{ $question->id }}" value="D">
                            <label class="form-check-label" for="option_d_{{ $question->id }}">D. {{ $question->option_d }}</label>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">
                    No questions available. Please add some questions first.
                </div>
            @endforelse

            @if ($questions->isNotEmpty())
                <button type="submit" class="btn btn-primary">Submit Exam</button>
            @endif
        </form>
    </div>

    <style>
        .form-check-input.custom-radio {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
            accent-color: #007bff; /* Blue color for the radio button */
            border: 2px solid #007bff; /* Ensure the border is visible */
        }
        .form-check-input.custom-radio:checked {
            background-color: #007bff;
        }
        .form-check-label {
            vertical-align: middle;
            font-size: 1rem;
            cursor: pointer;
        }
        .form-check {
            margin-bottom: 0.5rem;
        }
    </style>
@endsection