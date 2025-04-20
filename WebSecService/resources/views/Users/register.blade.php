@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 rounded-top-4">
                    <h4 class="mb-0"><i class="bi bi-person-plus me-2 text-primary"></i> Create Your Account</h4>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('doRegister') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control rounded-end-3" id="name" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control rounded-end-3" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}" required>
                                </div>
                                <div class="form-text">We'll send a verification email to this address</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="phone" class="form-label">Phone Number (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3"><i class="bi bi-phone"></i></span>
                                    <input type="tel" class="form-control rounded-end-3" id="phone" name="phone" 
                                           placeholder="+20 10XXXXXXXX" 
                                           pattern="^\+[0-9]{1,4}[0-9]{6,14}$"
                                           title="Please enter a valid phone number with country code (e.g. +201012345678)"
                                           value="{{ old('phone') }}">
                                </div>
                                <div class="form-text">Enter full international format with country code (e.g. +20 for Egypt)</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control rounded-end-3" id="password" name="password" placeholder="Create password" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control rounded-end-3" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                                </div>
                            </div>

                            <!-- Hidden fields for security question and answer to satisfy validation -->
                            <input type="hidden" name="security_question" value="Email Reset Enabled">
                            <input type="hidden" name="security_answer" value="{{ Str::random(24) }}">

                            <div class="col-md-12 mb-2">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary py-2 rounded-3">
                                        <i class="bi bi-person-plus-fill me-2"></i> Create Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="position-relative text-center my-4">
                        <hr class="my-0">
                        <div class="position-absolute top-50 start-50 translate-middle px-3 bg-white">
                            <span class="text-muted">OR</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mb-3">
                        <a href="/auth/google" class="btn btn-light d-flex align-items-center justify-content-center border py-2 px-3 w-100 rounded-3" style="max-width: 280px; background-color: white;">
                            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" style="height: 24px; margin-right: 10px;">
                            <span style="font-family: 'Roboto', sans-serif; font-weight: 500; color: #757575;">Continue with Google</span>
                        </a>
                    </div>
                    
                    <div class="text-center mb-3">
                        <small class="text-muted">If you sign up with Google, you'll be able to set a password for direct login from your profile page after registration.</small>
                    </div>

                    <div class="text-center mt-2">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Login here</a></p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-primary">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Enhanced Account Security</h5>
                            <p class="mb-0 text-muted">Adding your phone number enables two-factor authentication and also gives you an extra login method.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection