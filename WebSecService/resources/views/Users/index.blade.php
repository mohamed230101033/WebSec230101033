@extends('layouts.master')
@section('title', 'Users List')
@section('content')
    <div class="container">
        <h2 class="mt-4 mb-4">Users List</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filter Users -->
        <form action="{{ route('users.index') }}" method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="number" name="id" value="{{ request('id') }}" placeholder="Search by ID" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Search by Name" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" value="{{ request('email') }}" placeholder="Search by Email" class="form-control">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <a href="{{ route('users.create') }}" class="btn btn-success mb-3">Create New User</a>

        <!-- Users Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection