@extends('layouts.master')
@section('title', 'Verify Phone Number')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0"><i class="bi bi-phone me-2 text-primary"></i> Verify Your Phone Number</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {!! session('warning') !!}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <p>We need to verify your phone number to enable login via SMS.</p>
                        
                        @if(empty($phoneNumber))
                            <div class="alert alert-warning">
                                <p class="mb-0">You don't have a phone number in your profile. Please add one.</p>
                            </div>
                            
                            <form method="POST" action="{{ route('phone.update') }}" class="mt-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                        <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                               placeholder="+1234567890" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Enter your phone number with country code (e.g. +1 for US)</div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Phone Number</button>
                            </form>
                        @else
                            <div class="mb-4">
                                <p>Your current phone number: <strong>{{ $phoneNumber }}</strong></p>
                                
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        data-bs-toggle="collapse" data-bs-target="#updatePhoneForm">
                                    Change number
                                </button>
                                
                                <div class="collapse mt-3" id="updatePhoneForm">
                                    <form method="POST" action="{{ route('phone.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">New Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                                <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                       placeholder="+1234567890" value="{{ old('phone', $phoneNumber) }}" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Phone Number</button>
                                    </form>
                                </div>
                            </div>
                            
                            @if($hasCode)
                                <!-- Verification code form -->
                                <form method="POST" action="{{ route('phone.verify') }}" class="mt-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Verification Code</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                                                   placeholder="Enter 6-digit code" maxlength="6" required>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Enter the 6-digit code sent to your phone</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Verify</button>
                                </form>
                                
                                <div class="d-flex mt-4 justify-content-center">
                                    <form method="POST" action="{{ route('phone.send') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="bi bi-send me-2"></i> Send New Verification Code
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Send code button -->
                                <form method="POST" action="{{ route('phone.send') }}" class="mt-4">
                                    @csrf
                                    <p class="mb-3">Click the button below to send a verification code to your phone.</p>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-send me-2"></i> Send Verification Code
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 