@extends('layouts.master')
@section('title', 'Student Transcript')
@section('content')
    <div class="container">
        <h1 class="my-4">Student Transcript</h1>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Courses and Grades</h3>
            </div>
            <div class="card-body border"></div>
                <table class="table table-striped border-secondary rounded">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Course</th>
                            <th scope="col">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transcript as $entry)
                            <tr>
                                <td>{{ $entry['course'] }}</td>
                                <td>{{ $entry['grade'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No transcript data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to Home</a>
    </div>
@endsection