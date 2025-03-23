@extends('layouts.master')
@section('title', 'Verify Security Answer')
@section('content')
<div class="container mt-5">
    <h1>Verify Security Answer</h1>
    <p>{{ $security_question }}</p>
    <form method="POST" action="{{ route('password.check') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="form-group">
            <label for="security_answer">Answer</label>
            <input type="text" name="security_answer" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
    </form>
</div>
@endsection