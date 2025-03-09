@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-8 shadow-lg rounded-lg border border-gray-200">
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Welcome Back</h2>

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
        @csrf

        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" name="email" required 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 shadow-sm">
            Sign In
        </button>

        <p class="text-center text-gray-600 mt-4">
            Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Register now</a>
        </p>
    </form>
</div>
@endsection
