@extends('layout')

@section('title', $paper->title . ' - Research Portal')
@section('description', 'View details of your research paper: ' . $paper->title)

@section('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/review.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a> > Paper Details
        </div>
        
        <div class="review-header">
            <h1>{{ $paper->title }}</h1>
            <div class="status-badge status-{{ $paper->status }}">
                {{ ucfirst($paper->status) }}
            </div>
        </div>

        <div class="pdf-preview-section">
            <div class="pdf-preview-header">
                <h2>Paper Preview</h2>
                {{-- asset() - Generates URL for PDF file in public folder --}}
                {{-- asset($paper->pdf_path) converts "papers/file.pdf" to full URL --}}
                <a href="{{ asset($paper->pdf_path) }}" download class="download-btn-small" title="Download PDF">
                    <span>⬇</span>
                </a>
            </div>
            <div class="pdf-preview-container">
                {{-- PDF Display: <iframe> embeds PDF document inside the page --}}
                {{-- Browser's built-in PDF viewer renders the PDF --}}
                {{-- #toolbar=0 hides PDF toolbar for cleaner display --}}
                <iframe src="{{ asset($paper->pdf_path) }}#toolbar=0" class="pdf-iframe" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>

        <div class="paper-info-section">
            <div class="info-card">
                <h3>Paper Details</h3>
                <div class="info-grid">
                    <div class="info-item"><strong>Publication Year:</strong> {{ $paper->publication_year }}</div>
                    <div class="info-item"><strong>Uploaded:</strong> {{ $paper->created_at->format('M d, Y') }}</div>
                    <div class="info-item"><strong>Status:</strong> <span class="status-badge status-{{ $paper->status }}">{{ ucfirst($paper->status) }}</span></div>
                    <div class="info-item"><strong>Total Authors:</strong> {{ $paper->authors->count() }}</div>
                </div>
            </div>

            <div class="info-card">
                <h3>Abstract</h3>
                <p>{{ $paper->abstract }}</p>
            </div>

            <div class="info-card">
                <h3>Authors</h3>
                @foreach($paper->authors as $index => $author)
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

            <div class="text-center mt-20">
                <a href="{{ route('dashboard') }}" class="btn btn-outline">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
