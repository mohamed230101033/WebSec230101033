@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-center mb-4">Register</h2>

    @if (session('error'))
        <div class="text-red-500 text-center mb-4">{{ session('error') }}</div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
        @csrf

        <label>Name:</label>
        <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg">

        <label>Email:</label>
        <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg">

        <label>Password:</label>
        <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg">

        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded-lg">

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg">Register</button>

        <p class="text-center mt-3">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-600">Login</a>
        </p>
    </form>
</div>
@endsection
