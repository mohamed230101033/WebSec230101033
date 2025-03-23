@extends('layouts.master')
@section('title', 'Reset Password')
@section('content')
<div class="container mt-5">
    <h1>Reset Password</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
@endsection