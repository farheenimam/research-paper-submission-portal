@extends('layout')

@section('title', $paper->title . ' - Research Portal')
@section('description', Str::limit($paper->abstract, 160))

@section('content')
<div class="paper-view-public">
    <div class="container">
        <!-- Paper Header -->
        <div class="paper-header-public">
            <div class="breadcrumb">
                <a href="{{ route('welcome') }}">Home</a> > 
                <a href="{{ route('search') }}">Search</a> > 
                Paper Details
            </div>
            
            <h1 class="paper-title-public">{{ $paper->title }}</h1>
            
            <div class="paper-meta-public">
                <div class="meta-item">
                    <strong>Publication Year:</strong> {{ $paper->publication_year }}
                </div>
                <div class="meta-item">
                    <strong>Status:</strong> 
                    <span class="status-badge status-{{ $paper->status }}">
                        {{ ucfirst($paper->status) }}
                    </span>
                </div>
                <div class="meta-item">
                    <strong>Published:</strong> {{ $paper->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>

        <div class="paper-content-public">
            <!-- Authors Section -->
            <div class="paper-section">
                <h3>Authors</h3>
                <div class="authors-grid">
                    @foreach($paper->authors as $author)
                        <div class="author-card">
                            <div class="author-name">{{ $author->author_name }}</div>
                            @if($author->author_email)
                                <div class="author-email">
                                    <a href="mailto:{{ $author->author_email }}">{{ $author->author_email }}</a>
                                </div>
                            @endif
                            @if($author->affiliation)
                                <div class="author-affiliation">{{ $author->affiliation }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Abstract Section -->
            <div class="paper-section">
                <h3>Abstract</h3>
                <div class="abstract-content">
                    <p>{{ $paper->abstract }}</p>
                </div>
            </div>

            <!-- Download Section -->
            <div class="paper-section">
                <h3>Download</h3>
                <div class="download-section">
                    <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn btn-primary btn-large">
                        📄 Download PDF
                    </a>
                    <p class="download-info">Click to download the full research paper in PDF format</p>
                </div>
            </div>

            <!-- Citation Section -->
            <div class="paper-section">
                <h3>Citation</h3>
                <div class="citation-box">
                    <p class="citation-text">
                        @foreach($paper->authors as $index => $author){{ $author->author_name }}@if($index < $paper->authors->count() - 1), @endif @endforeach. 
                        ({{ $paper->publication_year }}). 
                        <em>{{ $paper->title }}</em>. 
                        Research Portal.
                    </p>
                    <button class="btn btn-outline btn-sm copy-citation" onclick="copyCitation()">Copy Citation</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/search.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script>
function copyCitation() {
    const citationText = document.querySelector('.citation-text').textContent;
    navigator.clipboard.writeText(citationText).then(function() {
        const button = document.querySelector('.copy-citation');
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.style.backgroundColor = '#28a745';
        button.style.color = '#ffffff';
        
        setTimeout(function() {
            button.textContent = originalText;
            button.style.backgroundColor = '';
            button.style.color = '';
        }, 2000);
    });
}
</script>
@endsection