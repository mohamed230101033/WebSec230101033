@extends('layouts.master')
@section('title', 'useers')
@section('content')
<div class="container">
    <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
        @csrf
        @isset($user) @method('PUT') @endisset
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control">
        </div>
        @empty($user)
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        @endempty
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
