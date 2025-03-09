@extends('layouts.master')
@section('title', 'Grades')
@section('content')
<div class="container mt-5">
    <h2 class="text-center">Grades</h2>
    <div class="text-end mb-3">
        <a href="{{ route('grades.create') }}" class="btn btn-success">Add Grade</a>
    </div>
    @foreach ($grades as $term => $termGrades)
        <div class="card mt-4">
            <div class="card-header">
                <h3>{{ $term }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Course Name</th>
                            <th>Grade</th>
                            <th>Credit Hours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($termGrades as $grade)
                            <tr>
                                <td>{{ $grade->course_name }}</td>
                                <td>{{ $grade->grade }}</td>
                                <td>{{ $grade->credit_hours }}</td>
                                <td>
                                    <a href="{{ route('grades.edit', $grade) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center mt-4">
                    <h4><strong>Total Credit Hours: {{ $termGrades->sum('credit_hours') }}</strong></h4>
                    <h4><strong>GPA: {{ calculateGPA($termGrades) }}</strong></h4>
                </div>
            </div>
        </div>
    @endforeach
    <div class="text-center mt-4">
        <h4><strong>Cumulative Credit Hours: {{ $grades->flatten()->sum('credit_hours') }}</strong></h4>
        <h4><strong>Cumulative GPA: {{ calculateGPA($grades->flatten()) }}</strong></h4>
    </div>
</div>
@endsection
