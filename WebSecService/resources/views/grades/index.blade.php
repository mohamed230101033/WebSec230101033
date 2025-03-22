@extends('layouts.master')

@section('title', 'GPA Calculator')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="text-black-50">GPA Calculator</h1>
            </div>
            <div class="col-md-5 text-md-right">
                <a href="{{ route('grades.create') }}" class="btn btn-success">Add Grade</a>
            </div>
        </div>

        @foreach ($semesterData as $term => $data)
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h3>{{ $term }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Course Name</th>
                                <th>Grade</th>
                                <th>Credit Hours</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['grades'] as $grade)
                                <tr>
                                    <td>{{ $grade->course_name }}</td>
                                    <td>{{ $grade->grade }}</td>
                                    <td>{{ $grade->credit_hours }}</td>
                                    <td>
                                        <a href="{{ route('grades.edit', $grade) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p><strong>Total Credit Hours:</strong> {{ $data['total_credit_hours'] }}</p>
                    <p><strong>GPA:</strong> {{ $data['gpa'] }}</p>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            <h4 class="text-secondary">Cumulative Credit Hours: {{ $totalCreditHours }}</h4>
            <h4 class="text-secondary">Cumulative GPA: {{ $cumulativeGPA }}</h4>
        </div>
    </div>
@endsection
