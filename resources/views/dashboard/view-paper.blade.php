@extends('layout')

@section('title', $paper->title . ' - Research Portal')
@section('description', 'View details of your research paper: ' . $paper->title)

@section('content')
<div class="paper-view-container">
    <div class="container">
        <div class="paper-header">
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a> > Paper Details
            </div>
            
            <div class="paper-status-header">
                <h1>{{ $paper->title }}</h1>
                <div class="status-badge status-{{ $paper->status }}">
                    {{ ucfirst($paper->status) }}
                </div>
            </div>
        </div>

        <div class="paper-content">
            <div class="paper-main">
                <div class="paper-section">
                    <h3>Abstract</h3>
                    <p class="abstract-text">{{ $paper->abstract }}</p>
                </div>

                <div class="paper-section">
                    <h3>Authors</h3>
                    <div class="authors-list">
                        @foreach($paper->authors as $author)
                            <div class="author-item">
                                <div class="author-name">{{ $author->author_name }}</div>
                                @if($author->author_email)
                                    <div class="author-email">{{ $author->author_email }}</div>
                                @endif
                                @if($author->affiliation)
                                    <div class="author-affiliation">{{ $author->affiliation }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="paper-sidebar">
                <div class="paper-info-card">
                    <h3>Paper Information</h3>
                    
                    <div class="info-item">
                        <label>Publication Year:</label>
                        <span>{{ $paper->publication_year }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Uploaded:</label>
                        <span>{{ $paper->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Status:</label>
                        <span class="status-text status-{{ $paper->status }}">
                            {{ ucfirst($paper->status) }}
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <label>Authors:</label>
                        <span>{{ $paper->authors->count() }}</span>
                    </div>
                </div>

                <div class="paper-actions-card">
                    <h3>Actions</h3>
                    
                    <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn btn-primary btn-full">
                        📄 Download PDF
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-full">
                        ← Back to Dashboard
                    </a>
                    
                    @if($paper->status === 'pending')
                        <div class="status-info">
                            <p><strong>Your paper is under review</strong></p>
                            <p>You will be notified once the review process is complete.</p>
                        </div>
                    @elseif($paper->status === 'approved')
                        <div class="status-info success">
                            <p><strong>Congratulations!</strong></p>
                            <p>Your paper has been approved and is now published.</p>
                        </div>
                    @elseif($paper->status === 'rejected')
                        <div class="status-info error">
                            <p><strong>Paper Rejected</strong></p>
                            <p>Please review the feedback and consider resubmitting with revisions.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
.paper-view-container {
    padding: 40px 0;
    min-height: calc(100vh - 200px);
}

.breadcrumb {
    color: #666666;
    margin-bottom: 20px;
    font-size: 14px;
}

.breadcrumb a {
    color: #2d5016;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.paper-status-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    gap: 20px;
}

.paper-status-header h1 {
    color: #2d5016;
    font-size: 32px;
    line-height: 1.3;
    flex: 1;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    flex-shrink: 0;
}

.paper-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
}

.paper-section {
    background: #ffffff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.paper-section h3 {
    color: #2d5016;
    margin-bottom: 20px;
    font-size: 24px;
}

.abstract-text {
    color: #333333;
    line-height: 1.7;
    font-size: 16px;
}

.authors-list {
    display: grid;
    gap: 15px;
}

.author-item {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #2d5016;
}

.author-name {
    font-weight: bold;
    color: #2d5016;
    margin-bottom: 5px;
}

.author-email {
    color: #666666;
    font-size: 14px;
    margin-bottom: 3px;
}

.author-affiliation {
    color: #666666;
    font-size: 14px;
    font-style: italic;
}

.paper-info-card,
.paper-actions-card {
    background: #ffffff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.paper-info-card h3,
.paper-actions-card h3 {
    color: #2d5016;
    margin-bottom: 20px;
    font-size: 20px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.info-item label {
    font-weight: bold;
    color: #333333;
}

.info-item span {
    color: #666666;
}

.status-text {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.btn-full {
    width: 100%;
    margin-bottom: 10px;
}

.status-info {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    background-color: #e7f3ff;
    border-left: 4px solid #007bff;
}

.status-info.success {
    background-color: #d4edda;
    border-left-color: #28a745;
}

.status-info.error {
    background-color: #f8d7da;
    border-left-color: #dc3545;
}

.status-info p {
    margin: 5px 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .paper-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .paper-status-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .paper-status-header h1 {
        font-size: 24px;
    }
}
</style>
@endsection