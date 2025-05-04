@extends('layouts.master')
@section('title', 'Welcome')
@section('content')
    <div class="container py-4">
        @if(isset($logout_success) && $logout_success)
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong><i class="bi bi-check-circle me-2"></i>Logged out successfully!</strong>
                <p class="mb-0">You have been securely logged out of your account.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
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
                        
                        <!-- Certificate Login Button -->
                        @if(isset($show_cert_login) && $show_cert_login)
                            <div class="mt-3 pt-3 border-top">
                                <p class="text-muted mb-2">If you have a valid certificate:</p>
                                <a href="/?cert_login=1" class="btn btn-success">
                                    <i class="bi bi-shield-lock me-1"></i> Login with Certificate
                                </a>
                            </div>
                        @endif
                    </div>
                @endguest
            </div>
        </div>
    </div>
@endsection