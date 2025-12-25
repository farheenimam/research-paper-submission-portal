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

        <!-- PDF Preview Section -->
        <div class="pdf-preview-section">
            <div class="pdf-preview-header">
                <h2>Paper Preview</h2>
                <a href="{{ asset($paper->pdf_path) }}" download class="download-btn-small" title="Download PDF">
                    <span>⬇</span>
                </a>
            </div>
            <div class="pdf-preview-container">
                <iframe src="{{ asset($paper->pdf_path) }}#toolbar=0" class="pdf-iframe" frameborder="0" allowfullscreen></iframe>
                <div class="pdf-fallback">
                    <p>If the PDF doesn't display, <a href="{{ asset($paper->pdf_path) }}" target="_blank">click here to open it in a new tab</a></p>
                </div>
            </div>
        </div>

        <div class="paper-content">
            <div class="paper-main">
                <!-- Paper Details Card -->
                <div class="paper-details-card">
                    <h3>Paper Details</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <label>Publication Year</label>
                            <span>{{ $paper->publication_year }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Uploaded</label>
                            <span>{{ $paper->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Status</label>
                            <span class="status-text status-{{ $paper->status }}">
                                {{ ucfirst($paper->status) }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Total Authors</label>
                            <span>{{ $paper->authors->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Abstract Section -->
                <div class="paper-section">
                    <h3>Abstract</h3>
                    <p class="abstract-text">{{ $paper->abstract }}</p>
                </div>

                <!-- Authors Section -->
                <div class="paper-section">
                    <h3>Authors</h3>
                    <div class="authors-list">
                        @foreach($paper->authors as $index => $author)
                            <div class="author-item">
                                <div class="author-number">Author {{ $index + 1 }}</div>
                                <div class="author-name">{{ $author->author_name }}</div>
                                @if($author->author_email)
                                    <div class="author-email">📧 {{ $author->author_email }}</div>
                                @endif
                                @if($author->affiliation)
                                    <div class="author-affiliation">🏢 {{ $author->affiliation }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Status Info -->
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

                <!-- Back Button -->
                <div class="back-button-section">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        @if($paper->comments->count() > 0)
            <div class="comments-section">
                <h2>Reviewer Comments</h2>
                <div class="comments-list">
                    @foreach($paper->comments as $comment)
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="comment-reviewer">
                                    <strong>{{ $comment->user->name ?? 'Reviewer' }}</strong>
                                    <span class="commenter-role">Reviewer</span>
                                </div>
                                <span class="comment-date">{{ $comment->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="comment-content">
                                <div class="comment-text">{!! nl2br(e($comment->comment)) !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="comments-section">
                <h2>Reviewer Comments</h2>
                <div class="no-comments">
                    <p>No comments from reviewers yet.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<!-- Paper View Styles - Updated -->
<style>
.paper-view-container {
    padding: 40px 0;
    min-height: calc(100vh - 200px);
    overflow: visible;
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

/* PDF Preview Section */
.pdf-preview-section {
    background: #ffffff !important;
    border-radius: 15px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    margin-bottom: 40px !important;
    overflow: hidden !important;
    display: block !important;
    width: 100% !important;
}

.pdf-preview-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 20px 30px !important;
    border-bottom: 2px solid #f0f0f0 !important;
    background: #f8f9fa !important;
}

.pdf-preview-header h2 {
    color: #2d5016 !important;
    font-size: 24px !important;
    margin: 0 !important;
    font-weight: 600 !important;
}

.download-btn-small {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 40px !important;
    height: 40px !important;
    background: #2d5016 !important;
    color: #ffffff !important;
    border-radius: 50% !important;
    text-decoration: none !important;
    font-size: 20px !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(45, 80, 22, 0.3) !important;
    flex-shrink: 0 !important;
    position: relative !important;
    z-index: 10 !important;
}

.download-btn-small:hover {
    background: #1f3a0f !important;
    transform: scale(1.1) !important;
    box-shadow: 0 4px 12px rgba(45, 80, 22, 0.4) !important;
}

.download-btn-small span {
    display: block !important;
    line-height: 1 !important;
}

.pdf-preview-container {
    position: relative !important;
    width: 100% !important;
    height: 600px !important;
    background: #e9ecef !important;
    display: block !important;
}

.pdf-iframe {
    width: 100% !important;
    height: 100% !important;
    border: none !important;
    display: block !important;
}

.pdf-fallback {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.9);
    padding: 10px 15px;
    border-radius: 5px;
    font-size: 12px;
    display: none;
}

.pdf-fallback a {
    color: #2d5016;
    text-decoration: underline;
}

.paper-content {
    overflow: visible !important;
    display: block !important;
    width: 100% !important;
}

.paper-main {
    max-width: 1000px !important;
    margin: 0 auto !important;
    width: 100% !important;
}

.paper-details-card {
    background: #ffffff !important;
    padding: 30px !important;
    border-radius: 15px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    margin-bottom: 30px !important;
    display: block !important;
    width: 100% !important;
}

.paper-details-card h3 {
    color: #2d5016;
    margin-bottom: 20px;
    font-size: 24px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-item label {
    font-weight: 600;
    color: #666666;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item span {
    color: #333333;
    font-size: 16px;
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
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #2d5016;
    transition: transform 0.2s ease;
}

.author-item:hover {
    transform: translateX(5px);
}

.author-number {
    font-size: 12px;
    color: #2d5016;
    font-weight: bold;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.author-name {
    font-weight: bold;
    color: #2d5016;
    font-size: 18px;
    margin-bottom: 8px;
}

.author-email {
    color: #666666;
    font-size: 14px;
    margin-bottom: 5px;
}

.author-affiliation {
    color: #666666;
    font-size: 14px;
    font-style: italic;
}

.status-text {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    display: inline-block;
}

.back-button-section {
    margin-top: 30px;
    text-align: center;
}

.status-info {
    padding: 20px;
    border-radius: 8px;
    background-color: #e7f3ff;
    border-left: 4px solid #007bff;
    margin-bottom: 30px;
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

/* Comments Section */
.comments-section {
    margin-top: 40px;
    background: #ffffff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.comments-section h2 {
    color: #2d5016;
    margin-bottom: 25px;
    font-size: 24px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.comments-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.comment-item {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #2d5016;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.comment-reviewer {
    display: flex;
    align-items: center;
    gap: 10px;
}

.comment-reviewer strong {
    color: #2d5016;
    font-size: 16px;
}

.commenter-role {
    background-color: #2d5016;
    color: #ffffff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
}

.comment-date {
    color: #666666;
    font-size: 14px;
}

.comment-content {
    color: #333333;
    line-height: 1.6;
    font-size: 15px;
}

.comment-text {
    word-wrap: break-word;
    white-space: pre-wrap;
}

.no-comments {
    text-align: center;
    padding: 40px 20px;
    color: #666666;
    font-style: italic;
}

@media (max-width: 768px) {
    .pdf-preview-container {
        height: 400px;
    }
    
    .pdf-preview-header {
        padding: 15px 20px;
    }
    
    .pdf-preview-header h2 {
        font-size: 20px;
    }
    
    .paper-status-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .paper-status-header h1 {
        font-size: 24px;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .comment-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection
