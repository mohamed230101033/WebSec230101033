@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0"><i class="bi bi-lock me-2 text-primary"></i> Login to Your Account</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('doLogin') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="login_id" class="form-label">Email or Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="login_id" name="login_id" 
                                       placeholder="Email or Phone Number" value="{{ old('login_id') }}" required>
                            </div>
                            <div class="form-text">Enter your email address or phone number (with country code)</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Password" required>
                            </div>
                        </div>
                        <div class="mb-4 text-end">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot your password?</a>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register now</a></p>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-primary">
                            <i class="bi bi-info-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Login with Phone Number</h5>
                            <p class="mb-0 text-muted">You can now log in using your verified phone number.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
