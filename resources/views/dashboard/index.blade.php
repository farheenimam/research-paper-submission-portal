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
                        {{-- @if - Blade directive: Check if condition is true --}}
                        {{-- $user->profile_photo - Access profile_photo property of user object --}}
                        {{-- asset() - Laravel helper: Generates URL for public folder files --}}
                        {{-- Example: asset('uploads/profiles/photo.jpg') => http://site.com/uploads/profiles/photo.jpg --}}
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
            {{-- route() - Laravel helper: Generates URL from named route --}}
            {{-- 'dashboard.upload-paper' - Route name defined in routes/web.php --}}
            {{-- Returns: /dashboard/upload-paper --}}
            <a href="{{ route('dashboard.upload-paper') }}" class="action-card upload-card">
                <div class="action-icon">📄</div>
                <h3>Upload Paper</h3>
                <p>Submit a new research paper for review</p>
            </a>
            
            <div class="action-card stats-card">
                <div class="action-icon">📊</div>
                {{-- $papers->count() - Collection method: Counts items in collection --}}
                {{-- Returns number of papers --}}
                <h3>{{ $papers->count() }}</h3>
                <p>Total Papers Uploaded</p>
            </div>
            
            <div class="action-card stats-card">
                <div class="action-icon">✅</div>
                {{-- $papers->where('status', 'approved') - Collection method: Filters papers --}}
                {{-- Only keeps papers where status equals 'approved' --}}
                {{-- ->count() - Counts filtered results --}}
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
                                <p class="authors-count">👥 {{ $paper->authors->count() }} Author(s)</p>
                                {{-- $paper->created_at - Carbon date object (Laravel's date library) --}}
                                {{-- ->format('M d, Y') - Formats date as "Jan 15, 2024" --}}
                                <p class="upload-date">📤 {{ $paper->created_at->format('M d, Y') }}</p>
                            </div>
                            
                            <div class="paper-abstract">
                                {{-- Str::limit() - Laravel helper: Truncates string to specified length --}}
                                {{-- Str::limit($text, 150) - Cuts text at 150 chars and adds "..." if longer --}}
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
{{-- asset() - Generates URL for CSS file in public folder --}}
{{-- asset('css/dashboard.css') => http://site.com/css/dashboard.css --}}
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection