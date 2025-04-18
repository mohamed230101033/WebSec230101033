@extends('layouts.master')
@section('title', 'Welcome')
@section('content')
    <div class="container py-4">
        @auth
            @if(!auth()->user()->hasVerifiedPhone() && auth()->user()->phone)
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <div class="me-3">
                        <i class="bi bi-phone fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">Verify Your Phone Number</h5>
                        <p class="mb-0">You can now login with your phone number. Please verify your phone number to enable this feature.</p>
                    </div>
                    <div>
                        <a href="{{ route('phone.verify') }}" class="btn btn-primary">Verify Now</a>
                    </div>
                </div>
            @elseif(!auth()->user()->phone)
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <div class="me-3">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">Add Your Phone Number</h5>
                        <p class="mb-0">Add a phone number to your profile to enable login via phone.</p>
                    </div>
                    <div>
                        <a href="{{ route('profile') }}" class="btn btn-warning">Update Profile</a>
                    </div>
                </div>
            @endif
        @endauth
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4">Welcome to WebSec Service</h3>
                <p>Your secure online shopping destination.</p>
                
                @guest
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
@endsection