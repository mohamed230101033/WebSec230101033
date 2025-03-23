@extends('layouts.master')
@section('title', 'Forgot Password')
@section('content')
<div class="container mt-5">
    <h1>Forgot Password</h1>
    <form action="{{ route('password.email') }}" method="post">
    @csrf
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group mb-2">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
    </div>

    <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
</div>
@endsection