@extends('layout')

@section('title', 'Register - Research Portal')
@section('description', 'Create your Research Portal account to access academic research and collaboration tools.')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Create Account</h2>
            <p>Join our community of researchers and reviewers</p>
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

        <form method="POST" action="{{route('signupPost')}}" enctype="multipart/form-data" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control" 
                       placeholder="Enter your full name" 
                       value="{{ old('name') }}" required maxlength="150">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="Enter your email address" 
                       value="{{ old('email') }}" required maxlength="150">
            </div>

            <div class="form-group">
                <label for="role_id" class="form-label">Role</label>
                @if(isset($selectedRole))
                    <!-- Role is pre-selected and cannot be changed -->
                    <input type="text" 
                           class="form-control role-readonly" 
                           value="{{ ucfirst($selectedRole->name) }}" 
                           readonly>
                    <input type="hidden" name="role_id" value="{{ $selectedRole->id }}">
                    <div class="help-text">
                        You are registering as a {{ ucfirst($selectedRole->name) }}. This cannot be changed.
                    </div>
                @else
                    <!-- Normal role selection -->
                    <select id="role_id" name="role_id" class="form-control">
                        <option value="">Select your role (defaults to User)</option>
                        @if(isset($roles))
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                        {{ (old('role_id') == $role->id) || 
                                           (empty(old('role_id')) && isset($defaultRole) && $defaultRole->id == $role->id) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="help-text">
                        If no role is selected, you will be registered as a User by default.
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="affiliation" class="form-label">Affiliation</label>
                <input type="text" id="affiliation" name="affiliation" class="form-control"
                       placeholder="University, Organization, or Company" 
                       value="{{ old('affiliation') }}" maxlength="255">
            </div>

            <div class="form-group">
                <label for="bio" class="form-label">Bio</label>
                <textarea id="bio" name="bio" class="form-control" 
                          placeholder="Tell us about yourself, your research interests, etc." 
                          maxlength="1000">{{ old('bio') }}</textarea>
            </div>

            <div class="form-group">
                <label for="profile_photo" class="form-label">Profile Photo</label>
                <input type="file" id="profile_photo" name="profile_photo" class="form-control"
                       accept="image/jpeg,image/png,image/jpg,image/gif">
                <div class="help-text">
                    Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Enter a strong password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password <span class="required">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                       placeholder="Confirm your password" required minlength="6">
            </div>

            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>

        <div class="auth-links">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</div>
@endsection