@extends('layouts.master')
@section('title', 'Questions')
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Questions</h1>
            <a href="{{ route('questions.create') }}" class="btn btn-success">Add Question</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Question</th>
                    <th>Option A</th>
                    <th>Option B</th>
                    <th>Option C</th>
                    <th>Option D</th>
                    <th>Correct Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($questions as $question)
                    <tr>
                        <td>{{ $question->question }}</td>
                        <td>{{ $question->option_a }}</td>
                        <td>{{ $question->option_b }}</td>
                        <td>{{ $question->option_c }}</td>
                        <td>{{ $question->option_d }}</td>
                        <td>{{ $question->correct_answer }}</td>
                        <td>
                            <a href="{{ route('questions.edit', $question) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('questions.destroy', $question) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No questions available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('questions.startExam') }}" class="btn btn-primary mt-3">Start Exam</a>
    </div>
@endsection