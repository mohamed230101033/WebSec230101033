@extends('layouts.master')
@section('title', 'File Encryption Utility')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Encrypt File</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('file.encrypt') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="encrypt_file" class="form-label">Select File to Encrypt</label>
                            <input type="file" class="form-control" id="encrypt_file" name="file" required>
                            <div class="form-text">Maximum file size: 10MB</div>
                        </div>
                        <div class="mb-3">
                            <label for="encrypt_password" class="form-label">Encryption Password</label>
                            <input type="password" class="form-control" id="encrypt_password" name="password" required minlength="8">
                            <div class="form-text">Minimum 8 characters</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-lock me-1"></i> Encrypt File
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Decrypt File</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('file.decrypt') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="decrypt_file" class="form-label">Select Encrypted File</label>
                            <input type="file" class="form-control" id="decrypt_file" name="file" required>
                            <div class="form-text">Maximum file size: 10MB</div>
                        </div>
                        <div class="mb-3">
                            <label for="decrypt_password" class="form-label">Decryption Password</label>
                            <input type="password" class="form-control" id="decrypt_password" name="password" required minlength="8">
                            <div class="form-text">Enter the password used for encryption</div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-unlock me-1"></i> Decrypt File
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">How to Use</h5>
                </div>
                <div class="card-body">
                    <h6>Encryption:</h6>
                    <ol>
                        <li>Select a file you want to encrypt (max 10MB)</li>
                        <li>Enter a strong password (min 8 characters)</li>
                        <li>Click "Encrypt File"</li>
                        <li>The encrypted file will be downloaded automatically</li>
                    </ol>
                    
                    <h6>Decryption:</h6>
                    <ol>
                        <li>Select the encrypted file</li>
                        <li>Enter the same password used for encryption</li>
                        <li>Click "Decrypt File"</li>
                        <li>The decrypted file will be downloaded automatically</li>
                    </ol>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Keep your password safe! If you lose it, you won't be able to decrypt your files.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 