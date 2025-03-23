@extends('layouts.master')

@section('title', 'Register')

@section('content')
    <div class="container mt-5">
        <h1>Register</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('doRegister') }}" method="post">
                    @csrf
                    <div class="form-group">
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">
                                <strong>Error!</strong> {{ $error }}
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group mb-2">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}"
                            required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="password_confirmation" class="form-label">Password Confirmation:</label>
                        <input type="password" class="form-control" placeholder="Confirm Password"
                            name="password_confirmation" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="security_question" class="form-label">Security Question:</label>
                        <select class="form-control" name="security_question" required>
                            <option value="" disabled selected>Select a security question</option>
                            <option value="What is your mother’s maiden name?" {{ old('security_question') == "What is your mother’s maiden name?" ? 'selected' : '' }}>What is your mother’s maiden name?</option>
                            <option value="What was the name of your first pet?" {{ old('security_question') == "What was the name of your first pet?" ? 'selected' : '' }}>What was the name of your first pet?</option>
                            <option value="What city were you born in?" {{ old('security_question') == "What city were you born in?" ? 'selected' : '' }}>What city were you born in?</option>
                            <option value="What was your childhood nickname?" {{ old('security_question') == "What was your childhood nickname?" ? 'selected' : '' }}>What was your childhood nickname?</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="security_answer" class="form-label">Security Answer:</label>
                        <input type="text" class="form-control" placeholder="Answer" name="security_answer"
                            value="{{ old('security_answer') }}" required>
                    </div>

                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection