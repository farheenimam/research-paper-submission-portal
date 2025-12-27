@extends('layout')

@section('title', 'My Profile - Research Portal')

@section('content')
<div class="profile-container">
    <div class="container">
        <div class="profile-header">
            <h1>My Profile</h1>
            <p>Manage your account information and preferences</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-content">
            <!-- Profile Photo Section -->
            <div class="profile-photo-section">
                <div class="current-photo">
                    @if($user->profile_photo)
                        <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" class="profile-photo-large">
                    @else
                        <div class="profile-photo-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <div class="photo-actions">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="photo-upload-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="affiliation" value="{{ $user->affiliation }}">
                        <input type="hidden" name="bio" value="{{ $user->bio }}">
                        
                        <div class="upload-buttons">
                            <div class="file-input-wrapper">
                                <input type="file" name="profile_photo" id="profile_photo" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif" 
                                       class="file-input"
                                       title="Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB">
                                <label for="profile_photo" class="btn btn-outline">Choose Photo</label>
                            </div>
                            <button type="submit" class="btn btn-primary" id="upload-btn" style="display: none;">Upload</button>
                        </div>
                    </form>
                    
                    @if($user->profile_photo)
                        <form action="{{ route('profile.remove-photo') }}" method="POST" class="remove-photo-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove your profile photo?')">Remove Photo</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Profile Information Form -->
            <div class="profile-form-section">
                <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               minlength="2"
                               maxlength="150"
                               pattern="[A-Za-z\s]{2,}"
                               title="Name must be at least 2 characters and contain only letters and spaces">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               maxlength="150"
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                               title="Please enter a valid email address">
                    </div>

                    <div class="form-group">
                        <label for="affiliation">Affiliation</label>
                        <input type="text" id="affiliation" name="affiliation" 
                               value="{{ old('affiliation', $user->affiliation) }}" 
                               placeholder="University, Institution, or Organization"
                               maxlength="255"
                               pattern=".{0,255}"
                               title="Affiliation must not exceed 255 characters">
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="4" 
                                  placeholder="Tell us about yourself, your research interests, and expertise..."
                                  maxlength="1000"
                                  title="Bio must not exceed 1000 characters">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline">Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="profile-stats">
            <h3>Account Statistics</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $user->papers->count() }}</div>
                    <div class="stat-label">Papers Published</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $user->created_at->format('M Y') }}</div>
                    <div class="stat-label">Member Since</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $user->role->name ?? 'User' }}</div>
                    <div class="stat-label">Account Type</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">

{{-- Profile photo upload JavaScript is now in common.js --}}
@endsection