@extends('layouts.master')
@section('title', 'Login')
@section('content')
<form action="{{ route('doLogin') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Error!</strong> {{$error}}
            </div>
        @endforeach
    </div>
    <div class="form-group mb-2">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="Email" name="email" required>
    </div>
    <div class="form-group mb-2">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="Password" name="password" required>
    </div>
    <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>
@endsection
