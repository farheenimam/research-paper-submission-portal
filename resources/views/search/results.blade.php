@extends('layout')

@section('title', 'Search Results - Research Portal')
@section('description', 'Search results for academic research papers on Research Portal.')

@section('content')
<div class="search-page">
    <!-- Search Header -->
    <div class="search-header">
        <div class="container">
            <div class="search-header-content">
                <h1>Research Portal</h1>
                <div class="search-container-page">
                    <form class="search-form-page" action="{{ route('search') }}" method="GET">
                        <input type="text" 
                               class="search-input-page" 
                               name="search"
                               value="{{ $query }}"
                               placeholder="Search research papers..."
                               autocomplete="off"
                               id="searchInput">
                        <button type="submit" class="search-btn-page">SEARCH</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="search-results">
        <div class="container">
            @if(!empty($query))
                <div class="results-header">
                    <div class="results-info-section">
                        <h2>Search Results</h2>
                        <p class="results-info">
                            @if($totalResults > 0)
                                Found {{ number_format($totalResults) }} result{{ $totalResults != 1 ? 's' : '' }} for "<strong>{{ $query }}</strong>"
                            @else
                                No results found for "<strong>{{ $query }}</strong>"
                            @endif
                        </p>
                    </div>
                </div>

                @if($papers->count() > 0)
                    <div class="papers-list">
                        @foreach($papers as $paper)
                            <div class="paper-result">
                                <div class="paper-result-header">
                                    <h3 class="paper-result-title">
                                        <a href="{{ route('paper.view', $paper->id) }}">{{ $paper->title }}</a>
                                    </h3>
                                    <div class="paper-result-meta">
                                        <span class="publication-year">{{ $paper->publication_year }}</span>
                                        <span class="separator">•</span>
                                        <span class="authors-count">{{ $paper->authors->count() }} author{{ $paper->authors->count() != 1 ? 's' : '' }}</span>
                                    </div>
                                </div>

                                <div class="paper-result-authors">
                                    <strong>Authors:</strong>
                                    @foreach($paper->authors as $index => $author)
                                        <span class="author-name">{{ $author->author_name }}</span>@if($index < $paper->authors->count() - 1), @endif
                                    @endforeach
                                </div>

                                <div class="paper-result-abstract">
                                    <p>{{ Str::limit($paper->abstract, 300) }}</p>
                                </div>

                                <div class="paper-result-actions">
                                    <a href="{{ route('paper.view', $paper->id) }}" class="btn btn-outline btn-sm">View Details</a>
                                    <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn btn-primary btn-sm">Download PDF</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($papers->hasPages())
                        <div class="pagination-wrapper">
                            {{ $papers->appends(['search' => $query])->links() }}
                        </div>
                    @endif
                @else
                    <div class="no-results">
                        <div class="no-results-icon">🔍</div>
                        <h3>No papers found</h3>
                        <p>Try different keywords or check your spelling</p>
                        <div class="search-suggestions">
                            <h4>Search tips:</h4>
                            <ul>
                                <li>Use different keywords</li>
                                <li>Check your spelling</li>
                                <li>Try more general terms</li>
                                <li>Use fewer words</li>
                            </ul>
                        </div>
                    </div>
                @endif
            @else
                <div class="search-prompt">
                    <div class="search-prompt-icon">📄</div>
                    <h2>Search Research Papers</h2>
                    <p>Enter keywords to find academic research papers</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/search.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    
    // Focus on search input when page loads
    if (searchInput) {
        searchInput.focus();
        
        // Move cursor to end of text
        const length = searchInput.value.length;
        searchInput.setSelectionRange(length, length);
    }
});
</script>
@endsection