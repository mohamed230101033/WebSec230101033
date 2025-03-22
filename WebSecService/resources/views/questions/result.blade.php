@extends('layouts.master')

@section('title', 'Exam Result')

@section('content')
    <div class="container mt-5">
        <h1>Exam Result</h1>

        <div class="card">
            <div class="card-body">
                <h5>Your Score: {{ $score }} / {{ $total }}</h5>
                <h5>Percentage: {{ round($percentage, 2) }}%</h5>
                @if ($percentage >= 60)
                    <div class="alert alert-success">Congratulations! You passed the exam.</div>
                @else
                    <div class="alert alert-danger">Sorry, you did not pass the exam. Try again!</div>
                @endif
                <a href="{{ route('questions.startExam') }}" class="btn btn-primary">Retake Exam</a>
                <a href="{{ route('questions.index') }}" class="btn btn-secondary">Back to Questions</a>
            </div>
        </div>
    </div>
@endsection