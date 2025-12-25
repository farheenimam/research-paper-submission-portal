@extends('layout')

@section('title', 'Login - Research Portal')
@section('description', 'Login to your Research Portal account to access academic research and collaboration tools.')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Login to your Research Portal account</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{route('loginPost')}}" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="Enter your email address" 
                       value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Enter your password" required>
            </div>

        

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="auth-links">
            <p>Don't have an account? <a href="{{ route('welcome') }}#join-us">Sign up here</a></p>
            <p><a href="#" class="forgot-password">Forgot your password?</a></p>
        </div>
    </div>
</div>
@endsection