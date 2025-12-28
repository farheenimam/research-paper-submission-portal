@extends('layout')

@section('title', 'Dashboard - Research Portal')
@section('description', 'Manage your research papers and profile on Research Portal.')

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="welcome-section">
                <h1>Welcome back, {{ $user->name }}!</h1>
                <p>Manage your research papers and profile</p>
            </div>
            
            <div class="profile-section">
                <div class="profile-card">
                    <div class="profile-image">
                        @if($user->profile_photo)
                            <img src="{{ asset($user->profile_photo) }}" alt="{{ $user->name }}" class="profile-img">
                        @else
                            <div class="profile-placeholder">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="profile-info">
                        <h3>{{ $user->name }}</h3>
                        <p class="role-badge">{{ ucfirst($user->role->name ?? 'User') }}</p>
                        @if($user->affiliation)
                            <p class="affiliation">{{ $user->affiliation }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('dashboard.upload-paper') }}" class="action-card upload-card">
                <div class="action-icon">📄</div>
                <h3>Upload Paper</h3>
                <p>Submit a new research paper for review</p>
            </a>
            
            <div class="action-card stats-card">
                <div class="action-icon">📊</div>
                <h3>{{ $papers->count() }}</h3>
                <p>Total Papers Uploaded</p>
            </div>
            
            <div class="action-card stats-card">
                <div class="action-icon">✅</div>
                <h3>{{ $papers->where('status', 'approved')->count() }}</h3>
                <p>Approved Papers</p>
            </div>
            
            <div class="action-card stats-card">
                <div class="action-icon">⏳</div>
                <h3>{{ $papers->where('status', 'pending')->count() }}</h3>
                <p>Pending Review</p>
            </div>
        </div>

        <!-- Recent Papers -->
        <div class="papers-section">
            <div class="section-header">
                <h2>Your Research Papers</h2>
                <a href="{{ route('dashboard.upload-paper') }}" class="btn btn-primary">Upload New Paper</a>
            </div>
            
            @if($papers->count() > 0)
                <div class="papers-grid">
                    @foreach($papers as $paper)
                        <div class="paper-card">
                            <div class="paper-status status-{{ $paper->status }}">
                                {{ ucfirst($paper->status) }}
                            </div>
                            
                            <h3 class="paper-title">{{ $paper->title }}</h3>
                            
                            <div class="paper-meta">
                                <p class="publication-year">📅 {{ $paper->publication_year }}</p>
                                <p class="upload-date">📤 {{ $paper->created_at->format('M d, Y') }}</p>
                            </div>
                            
                            <div class="paper-abstract">
                                <p>{{ Str::limit($paper->abstract, 150) }}</p>
                            </div>
                            
                            <div class="paper-actions">
                                <a href="{{ route('dashboard.view-paper', $paper->id) }}" class="btn btn-outline">View Details</a>
                                <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn btn-outline">Download PDF</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📄</div>
                    <h3>No papers uploaded yet</h3>
                    <p>Start by uploading your first research paper</p>
                    <a href="{{ route('dashboard.upload-paper') }}" class="btn btn-primary">Upload Your First Paper</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection