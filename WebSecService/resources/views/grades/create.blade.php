@extends('layouts.master')
@section('title', 'Add Grade')
@section('content')
<div class="container mt-5">
    <h2 class="text-center">Add Grade</h2>
    <form action="{{ route('grades.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" name="course_name" class="form-control @error('course_name') is-invalid @enderror" value="{{ old('course_name') }}">
            @error('course_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Grade</label>
            <input type="text" name="grade" class="form-control @error('grade') is-invalid @enderror" value="{{ old('grade') }}">
            @error('grade')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Credit Hours</label>
            <input type="number" name="credit_hours" class="form-control @error('credit_hours') is-invalid @enderror" value="{{ old('credit_hours') }}">
            @error('credit_hours')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Term</label>
            <input type="text" name="term" class="form-control @error('term') is-invalid @enderror" value="{{ old('term') }}">
            @error('term')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
