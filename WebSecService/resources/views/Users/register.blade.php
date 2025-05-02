@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="container py-5" style="min-height: 100vh; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-lg rounded-4" style="background: rgba(255, 255, 255, 0.95);">
                <div class="card-header bg-transparent py-3 rounded-top-4 border-bottom" style="border-color: #e9ecef !important;">
                    <h4 class="mb-0"><i class="bi bi-person-plus me-2" style="color: #4A6FA5;"></i> Create Your Account</h4>
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
                                <label for="name" class="form-label" style="color: #4A6FA5;">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3" style="background-color: #f8f9fa; border-color: #e9ecef;"><i class="bi bi-person" style="color: #4A6FA5;"></i></span>
                                    <input type="text" class="form-control rounded-end-3" id="name" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required style="border-color: #e9ecef;">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label" style="color: #4A6FA5;">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3" style="background-color: #f8f9fa; border-color: #e9ecef;"><i class="bi bi-envelope" style="color: #4A6FA5;"></i></span>
                                    <input type="email" class="form-control rounded-end-3" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}" required style="border-color: #e9ecef;">
                                </div>
                                <div class="form-text">We'll send a verification email to this address</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="phone" class="form-label" style="color: #4A6FA5;">Phone Number (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3" style="background-color: #f8f9fa; border-color: #e9ecef;"><i class="bi bi-phone" style="color: #4A6FA5;"></i></span>
                                    <input type="tel" class="form-control rounded-end-3" id="phone" name="phone" 
                                           placeholder="+20 10XXXXXXXX" 
                                           pattern="^\+[0-9]{1,4}[0-9]{6,14}$"
                                           title="Please enter a valid phone number with country code (e.g. +201012345678)"
                                           value="{{ old('phone') }}"
                                           style="border-color: #e9ecef;">
                                </div>
                                <div class="form-text">Enter full international format with country code (e.g. +20 for Egypt)</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label" style="color: #4A6FA5;">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3" style="background-color: #f8f9fa; border-color: #e9ecef;"><i class="bi bi-lock" style="color: #4A6FA5;"></i></span>
                                    <input type="password" class="form-control rounded-end-3" id="password" name="password" placeholder="Create password" required style="border-color: #e9ecef;">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label" style="color: #4A6FA5;">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3" style="background-color: #f8f9fa; border-color: #e9ecef;"><i class="bi bi-lock-fill" style="color: #4A6FA5;"></i></span>
                                    <input type="password" class="form-control rounded-end-3" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required style="border-color: #e9ecef;">
                                </div>
                            </div>

                            <!-- Hidden fields for security question and answer to satisfy validation -->
                            <input type="hidden" name="security_question" value="Email Reset Enabled">
                            <input type="hidden" name="security_answer" value="{{ Str::random(24) }}">

                            <div class="col-md-12 mb-2">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn py-2 rounded-3" 
                                            style="background: linear-gradient(135deg, #4A6FA5 0%, #6B8DB9 100%); color: white; border: none; transition: all 0.3s ease;"
                                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(74, 111, 165, 0.2)';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                        <i class="bi bi-person-plus-fill me-2"></i> Create Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="position-relative text-center my-4">
                        <hr class="my-0" style="border-color: #e9ecef;">
                        <div class="position-absolute top-50 start-50 translate-middle px-3 bg-white">
                            <span class="text-muted">OR</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-center gap-3 mb-3">
                        <!-- Google Login Button -->
                        <a href="/auth/google" class="btn d-flex align-items-center justify-content-center border py-2 px-3 w-100 rounded-3" 
                           style="max-width: 280px; background-color: #ffffff; color: #757575; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-color: #e9ecef;"
                           onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.12)';"
                           onmouseout="this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08)';">
                            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" style="height: 24px; margin-right: 10px;">
                            <span style="font-family: 'Roboto', sans-serif; font-weight: 500;">Sign up with Google</span>
                        </a>

                        <!-- Facebook Login Button -->
                        <a href="/auth/facebook" class="btn d-flex align-items-center justify-content-center py-2 px-3 w-100 rounded-3" 
                           style="max-width: 280px; background: linear-gradient(to right, #0082fb, #1877F2); color: white; transition: all 0.3s ease; border: none; box-shadow: 0 2px 4px rgba(24, 119, 242, 0.3);"
                           onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(24, 119, 242, 0.4)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(24, 119, 242, 0.3)';">
                            <i class="bi bi-facebook me-2" style="font-size: 1.2rem;"></i>
                            <span style="font-family: 'Roboto', sans-serif; font-weight: 500;">Sign up with Facebook</span>
                        </a>
                    </div>
                    
                    <div class="text-center mb-3">
                        <small class="text-muted">If you sign up with a social account, you'll be able to set a password for direct login from your profile page after registration.</small>
                    </div>

                    <div class="text-center mt-2">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-medium" style="color: #4A6FA5;">Login here</a></p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-lg rounded-4 mt-4" style="background: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3" style="color: #4A6FA5;">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1" style="color: #4A6FA5;">Enhanced Account Security</h5>
                            <p class="mb-0 text-muted">Adding your phone number enables two-factor authentication and also gives you an extra login method.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection