@extends('layouts.master')
@section('title', 'Market Bill')
@section('content')
@foreach ($student as $students)
    <div class="card m-4">
        <div class="card-header">Transcript for {{ $students['name'] }}</div>
        <div class="card-body">
            <p>Student ID: {{ $students['id'] }}</p>
            <p>Department: {{ $students['department'] }}</p>
            <p>GPA: {{ $students['gpa'] }}</p>
            <h5>Courses:</h5>
            <ul>
                @foreach ($students['courses'] as $course)
                    <li>{{ $course['course'] }} ({{ $course['code'] }}): {{ $course['grade'] }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endforeach
@endsection