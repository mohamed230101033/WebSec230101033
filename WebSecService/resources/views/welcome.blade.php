@extends('layouts.master')
@section('title', 'Welcome')

@section('content')
<div class="welcome-container">
    <!-- Hero Section -->
    <div class="hero-section mb-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1 order-2">
                <h1 class="display-4 fw-bold text-gradient mb-3">Secure Shopping for the Digital Age</h1>
                <p class="lead mb-4">Experience premium security with every transaction. Our platform ensures your data remains protected while you enjoy a seamless shopping experience.</p>
                
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm secure-login-btn">
                            <i class="bi bi-shield-lock me-2"></i>Secure Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg rounded-pill join-now-btn">
                            <i class="bi bi-person-plus me-2"></i>Join Now
                        </a>
                        
                        @if(isset($show_cert_login) && $show_cert_login)
                            <a href="/?cert_login=1" class="btn btn-info btn-lg rounded-pill ms-lg-2 mt-2 mt-lg-0 cert-login-btn">
                                <i class="bi bi-award me-2"></i>Certificate Login
                            </a>
                        @endif
                    @else
                        <a href="{{ route('products_list') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                            <i class="bi bi-shop me-2"></i>Browse Products
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-outline-primary btn-lg rounded-pill">
                            <i class="bi bi-person-circle me-2"></i>My Profile
                        </a>
                    @endguest
                </div>
                
                <div class="trust-indicators d-flex flex-wrap gap-4 mt-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary bg-opacity-10 me-2">
                            <i class="bi bi-shield-check text-primary"></i>
                        </div>
                        <span>SSL Encrypted</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary bg-opacity-10 me-2">
                            <i class="bi bi-credit-card text-primary"></i>
                        </div>
                        <span>Secure Payments</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary bg-opacity-10 me-2">
                            <i class="bi bi-lock text-primary"></i>
                        </div>
                        <span>Privacy Protected</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1 mb-4 mb-lg-0">
                <div class="position-relative hero-image-wrapper">
                    <div class="hero-image-container">
                        <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             class="img-fluid rounded-4 shadow-lg" alt="Secure Shopping">
                    </div>
                    <div class="floating-card floating-card-1 shadow">
                        <i class="bi bi-shield-check text-success"></i>
                        <span>Certified Secure</span>
                    </div>
                    <div class="floating-card floating-card-2 shadow">
                        <i class="bi bi-clock-history text-primary"></i>
                        <span>24/7 Protection</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($logout_success) && $logout_success)
        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">Logged Out Successfully</h5>
                    <p class="mb-0">You have been securely logged out of your account.</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @auth
        @if(!auth()->user()->hasVerifiedPhone() && auth()->user()->phone)
            <div class="alert custom-alert-info d-flex align-items-center rounded-3 shadow-sm border-0 mb-4" role="alert">
                <div class="alert-icon me-3">
                    <i class="bi bi-phone"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Verify Your Phone Number</h5>
                    <p class="mb-0">Enable phone login by verifying your number for enhanced account security.</p>
                </div>
                <div>
                    <a href="{{ route('phone.verify') }}" class="btn btn-info text-white rounded-pill">
                        <i class="bi bi-shield-check me-1"></i>Verify Now
                    </a>
                </div>
            </div>
        @elseif(!auth()->user()->phone)
            <div class="alert custom-alert-warning d-flex align-items-center rounded-3 shadow-sm border-0 mb-4" role="alert">
                <div class="alert-icon me-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Add Your Phone Number</h5>
                    <p class="mb-0">Enhance your account security by adding a phone number for two-factor authentication.</p>
                </div>
                <div>
                    <a href="{{ route('profile') }}" class="btn btn-warning rounded-pill">
                        <i class="bi bi-pencil me-1"></i>Update Profile
                    </a>
                </div>
            </div>
        @endif
    @endauth
    
    <!-- Features Section -->
    <div class="features-section my-5 py-3">
        <h2 class="text-center fw-bold mb-5">Why Choose WebSec Service?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card h-100">
                    <div class="feature-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h3>Advanced Security</h3>
                    <p>Our platform employs cutting-edge security measures including SSL encryption, certificate authentication, and secure payment processing.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card h-100">
                    <div class="feature-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <h3>Fast Performance</h3>
                    <p>Enjoy lightning-fast page loads and responsive interface, making your shopping experience smooth and efficient.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card h-100">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3>X.509 Certificate Auth</h3>
                    <p>Experience passwordless authentication with X.509 certificates for enhanced security. Our platform supports client-side certificate verification for secure access.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Authentication Section -->
    <div class="cert-auth-section my-5 py-4 bg-light rounded-4">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 mb-4 mb-lg-0 d-flex justify-content-center align-items-center">
                <div class="cert-image-container text-center w-100">
                    <img src="https://www.globalsign.com/application/files/2817/2044/9274/Understanding_X.509_GlobaSign.jpg" 
                         class="img-fluid rounded-4 shadow cert-image" alt="X.509 Digital Certificate">
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">X.509 Certificate Authentication</h2>
                <p class="lead mb-4">Experience the latest in secure authentication technology with our X.509 certificate implementation.</p>
                <div class="cert-features">
                    <div class="cert-feature d-flex align-items-start mb-3">
                        <div class="cert-icon me-3 mt-1">
                            <i class="bi bi-fingerprint text-primary"></i>
                        </div>
                        <div>
                            <h5>Passwordless Authentication</h5>
                            <p>Sign in securely without passwords using cryptographic client certificates, eliminating password-related vulnerabilities.</p>
                        </div>
                    </div>
                    <div class="cert-feature d-flex align-items-start mb-3">
                        <div class="cert-icon me-3 mt-1">
                            <i class="bi bi-shield-lock-fill text-primary"></i>
                        </div>
                        <div>
                            <h5>Public Key Infrastructure</h5>
                            <p>Our platform leverages PKI to verify identities with mathematical certainty, preventing impersonation attacks.</p>
                        </div>
                    </div>
                    <div class="cert-feature d-flex align-items-start">
                        <div class="cert-icon me-3 mt-1">
                            <i class="bi bi-check2-circle text-primary"></i>
                        </div>
                        <div>
                            <h5>Seamless User Experience</h5>
                            <p>Once your certificate is installed, enjoy automatic secure authentication with no additional steps required.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .welcome-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .secure-login-btn {
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    
    .secure-login-btn:hover {
        background-color: #0dcaf0;
        transform: scale(1.05);
    }
    
    .join-now-btn {
        transition: color 0.3s ease, border-color 0.3s ease;
    }
    
    .join-now-btn:hover {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .cert-login-btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .cert-login-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(13, 202, 240, 0.4);
    }
    
    .cert-login-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: all 0.6s ease;
    }
    
    .cert-login-btn:hover::before {
        left: 100%;
    }
    
    .text-gradient {
        background: linear-gradient(90deg, #0d6efd, #0dcaf0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .hero-image-wrapper {
        position: relative;
        width: 100%;
        padding: 10px;
    }
    
    .hero-image-container {
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin: 0 auto;
        max-width: 600px;
    }
    
    .hero-image-container img {
        transition: transform 0.5s ease;
        width: 100%;
        height: auto;
        object-fit: cover;
        aspect-ratio: 4/3;
    }
    
    .hero-image-container:hover img {
        transform: scale(1.02);
    }
    
    .floating-card {
        position: absolute;
        background: white;
        padding: 0.75rem 1.25rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        z-index: 10;
    }
    
    .floating-card i {
        font-size: 1.5rem;
    }
    
    .floating-card-1 {
        top: 10%;
        right: 0;
        transform: translateX(10%);
        animation: float 3s ease-in-out infinite;
    }
    
    .floating-card-2 {
        bottom: 10%;
        left: 0;
        transform: translateX(-10%);
        animation: float 3s ease-in-out infinite;
        animation-delay: 1.5s;
    }
    
    @media (max-width: 768px) {
        .floating-card {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            gap: 0.5rem;
        }
        
        .floating-card i {
            font-size: 1.25rem;
        }
        
        .floating-card-1 {
            top: 5%;
            right: 5%;
            transform: none;
        }
        
        .floating-card-2 {
            bottom: 5%;
            left: 5%;
            transform: none;
        }
    }
    
    @keyframes float {
        0% { transform: translateY(0px) translateX(var(--translateX, 0)); }
        50% { transform: translateY(-10px) translateX(var(--translateX, 0)); }
        100% { transform: translateY(0px) translateX(var(--translateX, 0)); }
    }
    
    .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .feature-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        color: white;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1.5rem;
    }
    
    .custom-alert-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .custom-alert-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .alert-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .custom-alert-info .alert-icon {
        background-color: rgba(13, 202, 240, 0.2);
        color: #0dcaf0;
    }
    
    .custom-alert-warning .alert-icon {
        background-color: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }

    .cert-auth-section {
        padding: 3rem;
        background-color: rgba(13, 110, 253, 0.03);
    }

    .cert-image-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 320px;
        min-height: 220px;
        max-height: 350px;
        width: 100%;
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .cert-image {
        max-width: 90%;
        max-height: 300px;
        width: auto;
        height: auto;
        margin: 0 auto;
        display: block;
        transition: transform 0.5s ease;
    }

    .cert-image-container:hover .cert-image {
        transform: scale(1.03);
    }

    .cert-icon {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background-color: rgba(13, 110, 253, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
    }

    .cert-features {
        border-left: 3px solid var(--primary-color);
        padding-left: 1.5rem;
    }

    @media (max-width: 992px) {
        .cert-auth-section {
            padding: 2rem 0.5rem;
        }
        .cert-image-container {
            height: 200px;
            min-height: 120px;
            max-height: 220px;
        }
        .cert-image {
            max-height: 180px;
        }
    }

    @media (max-width: 576px) {
        .cert-image-container {
            height: 120px;
            min-height: 80px;
            max-height: 140px;
        }
        .cert-image {
            max-height: 100px;
        }
    }
</style>
@endsection