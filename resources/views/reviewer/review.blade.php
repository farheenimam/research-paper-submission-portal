@extends('layout')

@section('title', 'Review: ' . $paper->title . ' - Research Portal')
@section('description', 'Review research paper as a reviewer.')

@section('content')
<div class="review-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('reviewer.articles') }}">Articles</a> > Review Paper
        </div>

        <!-- Paper Header -->
        <div class="review-header">
            <h1>{{ $paper->title }}</h1>
            <div class="paper-status-badge status-{{ $paper->status }}">
                {{ ucfirst($paper->status) }}
            </div>
        </div>

        <!-- Paper Information -->
        <div class="paper-info-section">
            <div class="info-card">
                <h3>Paper Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Publication Year:</strong> {{ $paper->publication_year }}
                    </div>
                    <div class="info-item">
                        <strong>Uploaded by:</strong> {{ $paper->uploader->name ?? 'Unknown' }}
                    </div>
                    <div class="info-item">
                        <strong>Upload Date:</strong> {{ $paper->created_at->format('M d, Y') }}
                    </div>
                    <div class="info-item">
                        <strong>Authors:</strong> {{ $paper->authors->count() }} author(s)
                    </div>
                </div>
            </div>

            <!-- Authors -->
            <div class="info-card">
                @php
                    // Get all authors from paper_authors table for this paper_id
                    $allAuthors = \App\Models\PaperAuthor::where('paper_id', $paper->id)
                        ->orderBy('id', 'asc')
                        ->get();
                @endphp
                <h3>Authors ({{ $allAuthors->count() }})</h3>
                @if($allAuthors->count() > 0)
                    <div class="authors-list">
                        @foreach($allAuthors as $index => $author)
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
                @else
                    <p class="no-authors">No authors found for this paper.</p>
                @endif
            </div>

            <!-- Abstract -->
            <div class="info-card">
                <h3>Abstract</h3>
                <div class="abstract-content">
                    <p>{{ $paper->abstract }}</p>
                </div>
            </div>

            <!-- Download -->
            <div class="info-card">
                <h3>Paper Document</h3>
                <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn btn-primary">
                    📄 Download PDF
                </a>
            </div>
        </div>

        <!-- Review Form -->
        <div class="review-form-section">
            <h2>Review Paper</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reviewer.update-review', $paper->id) }}" method="POST" class="review-form">
                @csrf
                @method('PUT')

                <!-- Status Selection -->
                <div class="form-group">
                    <label for="status" class="form-label">Change Status <span class="required">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $paper->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $paper->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $paper->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Comment -->
                <div class="form-group">
                    <label for="comment" class="form-label">Add Comment</label>
                    <textarea name="comment" id="comment" class="form-control" rows="6" 
                              placeholder="Enter your review comments, feedback, or suggestions for the author..."></textarea>
                    <div class="help-text">Your comment will be saved with this review</div>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <a href="{{ route('reviewer.articles') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>

        <!-- Previous Comments -->
        @if($paper->comments->count() > 0)
            <div class="comments-section">
                <h2>Previous Comments</h2>
                <div class="comments-list">
                    @foreach($paper->comments as $comment)
                        <div class="comment-item">
                            <div class="comment-header">
                                <strong>{{ $comment->user->name }}</strong>
                                <span class="comment-date">{{ $comment->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="comment-content">
                                <p>{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/review.css') }}" rel="stylesheet">
@endsection

