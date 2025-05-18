@extends('layouts.master')
@section('title', 'Login')
@section('content')
    <div class="container py-5" style="min-height: 100vh; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4" style="background: rgba(255, 255, 255, 0.95);">
                    <div class="card-header bg-transparent py-3 rounded-top-4 border-bottom" style="border-color: #e9ecef !important;">
                        <h4 class="mb-0"><i class="bi bi-lock me-2" style="color: #4A6FA5;"></i> Login to Your Account</h4>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('doLogin') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="login_id" class="form-label" style="color: #4A6FA5;">Email or Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3" style="background-color: #f8f9fa; border-color: #e9ecef;"><i class="bi bi-person" style="color: #4A6FA5;"></i></span>
                                    <input type="text" class="form-control rounded-end-3" id="login_id" name="login_id"
                                        placeholder="Email or Phone Number" value="{{ old('login_id') }}" required
                                        style="border-color: #e9ecef;">
                                </div>
                                <div class="form-text">Enter your email address or phone number (with country code)</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label" style="color: #4A6FA5;">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3" style="background-color: #f8f9fa; border-color: #e9ecef;"><i class="bi bi-key" style="color: #4A6FA5;"></i></span>
                                    <input type="password" class="form-control rounded-end-3" id="password" name="password"
                                        placeholder="Password" required style="border-color: #e9ecef;">
                                </div>
                            </div>
                            <div class="mb-4 text-end">
                                <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #4A6FA5;">Forgot your password?</a>
                            </div>
                            <div class="d-grid gap-2 mb-4">
                                <button type="submit" class="btn py-2 rounded-3" 
                                        style="background: linear-gradient(135deg, #4A6FA5 0%, #6B8DB9 100%); color: white; border: none; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(74, 111, 165, 0.2)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                                </button>
                            </div>

                            <div class="position-relative text-center mb-4">
                                <hr class="my-0" style="border-color: #e9ecef;">
                                <div class="position-absolute top-50 start-50 translate-middle px-3 bg-white">
                                    <span class="text-muted">OR</span>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-3 mb-4">
                                <!-- Google Login Button -->
                                <a href="{{ route('login_with_google') }}" class="btn d-flex align-items-center justify-content-center border py-2 px-3 rounded-3" 
                                   style="background-color: #ffffff; color: #757575; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-color: #e9ecef;"
                                   onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.12)';"
                                   onmouseout="this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08)';">
                                    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" style="height: 24px; margin-right: 10px;">
                                    <span style="font-family: 'Roboto', sans-serif; font-weight: 500;">Sign in with Google</span>
                                </a>
                                
                                <!-- Facebook Login Button -->
                                <a href="{{ route('login_with_facebook') }}" class="btn d-flex align-items-center justify-content-center py-2 px-3 rounded-3" 
                                   style="background: linear-gradient(to right, #0082fb, #1877F2); color: white; transition: all 0.3s ease; border: none; box-shadow: 0 2px 4px rgba(24, 119, 242, 0.3);"
                                   onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(24, 119, 242, 0.4)';"
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(24, 119, 242, 0.3)';">
                                    <i class="bi bi-facebook me-2" style="font-size: 1.2rem;"></i>
                                    <span style="font-family: 'Roboto', sans-serif; font-weight: 500;">Sign in with Facebook</span>
                                </a>
                            </div>
                        </form>

                        <div class="text-center mt-1">
                            <p class="mb-0">Don't have an account? <a href="{{ route('register') }}"
                                    class="text-decoration-none fw-medium" style="color: #4A6FA5;">Register now</a></p>
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
                                <h5 class="mb-1" style="color: #4A6FA5;">Secure Login Options</h5>
                                <p class="mb-0 text-muted">You can log in using your email, verified phone number, Google account, or Facebook account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection