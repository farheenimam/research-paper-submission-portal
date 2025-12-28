@extends('layout')

@section('title', $paper->title . ' - Admin Review')
@section('description', 'Review and manage research paper: ' . $paper->title)

@section('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/review.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a> > Paper Details
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
                <a href="{{ asset($paper->pdf_path) }}" download class="download-btn-small" title="Download PDF">
                    <span>⬇</span>
                </a>
            </div>
            <div class="pdf-preview-container">
                {{-- PDF Display: <iframe> embeds PDF document inside the page --}}
                {{-- asset() - Generates full URL: "papers/file.pdf" => "http://site.com/papers/file.pdf" --}}
                {{-- Browser's built-in PDF viewer automatically renders the PDF --}}
                {{-- #toolbar=0 - Hides PDF toolbar for cleaner display --}}
                <iframe src="{{ asset($paper->pdf_path) }}#toolbar=0" class="pdf-iframe" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>

        <div class="paper-info-section">
            <div class="info-card">
                <h3>Paper Details</h3>
                <div class="info-grid">
                    <div class="info-item"><strong>Publication Year:</strong> {{ $paper->publication_year }}</div>
                    <div class="info-item"><strong>Uploaded:</strong> {{ $paper->created_at->format('M d, Y') }}</div>
                    <div class="info-item"><strong>Uploaded By:</strong> {{ $paper->uploader->name ?? 'N/A' }}</div>
                    <div class="info-item"><strong>Category:</strong> {{ $paper->categories->pluck('name')->implode(', ') }}</div>
                </div>
            </div>

            <div class="info-card">
                <h3>Authors</h3>
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

            <div class="info-card">
                <h3>Abstract</h3>
                <p>{{ $paper->abstract }}</p>
            </div>

        </div>

        <div class="admin-actions-sidebar">
            <div class="card">
                <h3>Admin Actions</h3>
                
                @if($paper->status == 'pending')
                    <form action="{{ route('admin.approve-paper', $paper->id) }}" method="POST" class="review-form">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-full">Approve Paper</button>
                    </form>

                    <form action="{{ route('admin.reject-paper', $paper->id) }}" method="POST" class="review-form">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger btn-full">Reject Paper</button>
                    </form>
                @else
                    <div class="status-info">
                        <p><strong>Status:</strong> {{ ucfirst($paper->status) }}</p>
                        @if($paper->approved_by)
                            <p><strong>Reviewed by:</strong> {{ \App\Models\User::find($paper->approved_by)->name ?? 'Admin' }}</p>
                        @endif
                    </div>
                @endif

                <div class="mt-20">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-full">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

