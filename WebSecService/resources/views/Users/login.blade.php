@extends('layouts.master')
@section('title', 'Login')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 rounded-top-4">
                        <h4 class="mb-0"><i class="bi bi-lock me-2 text-primary"></i> Login to Your Account</h4>
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
                                <label for="login_id" class="form-label">Email or Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control rounded-end-3" id="login_id" name="login_id"
                                        placeholder="Email or Phone Number" value="{{ old('login_id') }}" required>
                                </div>
                                <div class="form-text">Enter your email address or phone number (with country code)</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-3"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control rounded-end-3" id="password" name="password"
                                        placeholder="Password" required>
                                </div>
                            </div>
                            <div class="mb-4 text-end">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot your
                                    password?</a>
                            </div>
                            <div class="d-grid gap-2 mb-4">
                                <button type="submit" class="btn btn-primary py-2 rounded-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                                </button>
                            </div>

                            <div class="position-relative text-center mb-4">
                                <hr class="my-0">
                                <div class="position-absolute top-50 start-50 translate-middle px-3 bg-white">
                                    <span class="text-muted">OR</span>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-3 mb-4">
                                <a href="{{ route('login_with_google') }}" class="btn btn-light d-flex align-items-center justify-content-center border py-2 px-3 rounded-3" style="background-color: white;">
                                    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" style="height: 24px; margin-right: 10px;">
                                    <span style="font-family: 'Roboto', sans-serif; font-weight: 500; color: #757575;">Continue with Google</span>
                                </a>
                                
                                <a href="{{ route('login_with_facebook') }}" class="btn btn-light d-flex align-items-center justify-content-center border py-2 px-3 rounded-3" style="background-color: white;">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/1024px-Facebook_Logo_%282019%29.png" alt="Facebook logo" style="height: 24px; margin-right: 10px;">
                                    <span style="font-family: 'Roboto', sans-serif; font-weight: 500; color: #757575;">Continue with Facebook</span>
                                </a>
                            </div>
                        </form>

                        <div class="text-center mt-1">
                            <p class="mb-0">Don't have an account? <a href="{{ route('register') }}"
                                    class="text-decoration-none fw-medium">Register now</a></p>
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
                                <h5 class="mb-1">Secure Login Options</h5>
                                <p class="mb-0 text-muted">You can log in using your email, verified phone number, Google account, or Facebook account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection