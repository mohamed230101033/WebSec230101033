@extends('layouts.master')

@section('title', 'Edit Question')

@section('content')
    <div class="container mt-5">
        <h1>Edit Question</h1>

        <form action="{{ route('questions.update', $question) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <textarea class="form-control" id="question" name="question" required>{{ $question->question }}</textarea>
            </div>
            <div class="mb-3">
                <label for="option_a" class="form-label">Option A</label>
                <input type="text" class="form-control" id="option_a" name="option_a" value="{{ $question->option_a }}" required>
            </div>
            <div class="mb-3">
                <label for="option_b" class="form-label">Option B</label>
                <input type="text" class="form-control" id="option_b" name="option_b" value="{{ $question->option_b }}" required>
            </div>
            <div class="mb-3">
                <label for="option_c" class="form-label">Option C</label>
                <input type="text" class="form-control" id="option_c" name="option_c" value="{{ $question->option_c }}" required>
            </div>
            <div class="mb-3">
                <label for="option_d" class="form-label">Option D</label>
                <input type="text" class="form-control" id="option_d" name="option_d" value="{{ $question->option_d }}" required>
            </div>
            <div class="mb-3">
                <label for="correct_answer" class="form-label">Correct Answer</label>
                <select class="form-control" id="correct_answer" name="correct_answer" required>
                    <option value="A" {{ $question->correct_answer == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ $question->correct_answer == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ $question->correct_answer == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ $question->correct_answer == 'D' ? 'selected' : '' }}>D</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('questions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection