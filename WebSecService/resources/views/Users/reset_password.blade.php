@extends('layouts.master')

@section('content')
<style>
    .reset-container {
        min-height: 100vh;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .reset-card {
        background: white;
        width: 100%;
        max-width: 400px;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .reset-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .reset-icon {
        background: #4A90E2;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .reset-icon i {
        color: white;
        font-size: 24px;
    }

    .reset-title {
        color: #333;
        font-size: 24px;
        margin-bottom: 0.5rem;
    }

    .reset-subtitle {
        color: #666;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-input {
        width: 100%;
        padding: 12px 40px 12px 40px;
        border: 2px solid #e1e1e1;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-input:focus {
        border-color: #4A90E2;
        outline: none;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        color: #666;
        cursor: pointer;
        padding: 0;
    }

    .toggle-password:hover {
        color: #4A90E2;
    }

    .requirements {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin: 12px 0;
    }

    .requirement-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 12px;
    }

    .requirement-item.valid {
        color: #4CAF50;
    }

    .requirement-item i {
        font-size: 14px;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background: #4A90E2;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }

    .submit-btn:hover {
        background: #357ABD;
        transform: translateY(-1px);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .security-note {
        text-align: center;
        margin-top: 1.5rem;
        color: #666;
        font-size: 13px;
    }

    .security-note i {
        color: #4A90E2;
        margin-right: 6px;
    }

    .alert {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 14px;
    }

    .alert-danger {
        background: #FFE8E8;
        color: #D32F2F;
        border: 1px solid #FFCDD2;
    }

    .alert-info {
        background: #E3F2FD;
        color: #1976D2;
        border: 1px solid #BBDEFB;
    }
</style>

<div class="reset-container">
    <div class="reset-card">
        <div class="reset-header">
            <div class="reset-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h1 class="reset-title">Reset Password</h1>
            <p class="reset-subtitle">Create a strong password for your account</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
            @csrf

            <div class="form-group">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" 
                       class="form-input @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required
                       placeholder="New Password">
                <button type="button" class="toggle-password" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            <div class="requirements">
                <div class="requirement-item" data-requirement="length">
                    <i class="bi bi-circle"></i>
                    <span>8+ Characters</span>
                </div>
                <div class="requirement-item" data-requirement="case">
                    <i class="bi bi-circle"></i>
                    <span>Upper & Lower</span>
                </div>
                <div class="requirement-item" data-requirement="number">
                    <i class="bi bi-circle"></i>
                    <span>Number</span>
                </div>
                <div class="requirement-item" data-requirement="special">
                    <i class="bi bi-circle"></i>
                    <span>Special Char</span>
                </div>
            </div>

            <div class="form-group">
                <i class="bi bi-lock-fill input-icon"></i>
                <input type="password" 
                       class="form-input" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       placeholder="Confirm Password">
                <button type="button" class="toggle-password" id="toggleConfirmPassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            <button type="submit" class="submit-btn">
                Set New Password
            </button>
        </form>

        <div class="security-note">
            <i class="bi bi-shield-check"></i>
            <span>Enterprise-grade encryption</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function togglePasswordVisibility(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        
        button.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            const icon = button.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    }

    togglePasswordVisibility('password', 'togglePassword');
    togglePasswordVisibility('password_confirmation', 'toggleConfirmPassword');

    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');
    const form = document.querySelector('form');

    function validatePassword(value) {
        const requirements = {
            length: value.length >= 8,
            case: /[a-z]/.test(value) && /[A-Z]/.test(value),
            number: /\d/.test(value),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(value)
        };

        Object.entries(requirements).forEach(([key, met]) => {
            const item = document.querySelector(`[data-requirement="${key}"]`);
            const icon = item.querySelector('i');
            
            if (met) {
                item.classList.add('valid');
                icon.classList.remove('bi-circle');
                icon.classList.add('bi-check-circle-fill');
            } else {
                item.classList.remove('valid');
                icon.classList.remove('bi-check-circle-fill');
                icon.classList.add('bi-circle');
            }
        });

        return Object.values(requirements).every(Boolean);
    }

    password.addEventListener('input', () => validatePassword(password.value));

    form.addEventListener('submit', function(event) {
        if (!validatePassword(password.value)) {
            event.preventDefault();
            return;
        }
        
        if (password.value !== confirmation.value) {
            event.preventDefault();
            confirmation.setCustomValidity("Passwords don't match");
        } else {
            confirmation.setCustomValidity('');
        }
    });

    confirmation.addEventListener('input', function() {
        this.setCustomValidity(password.value === this.value ? '' : "Passwords don't match");
    });
});
</script>
@endsection