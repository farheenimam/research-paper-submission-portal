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
                                About {{ number_format($totalResults) }} result{{ $totalResults != 1 ? 's' : '' }} (0.09 sec)
                            @else
                                No results found for "<strong>{{ $query }}</strong>"
                            @endif
                        </p>
                    </div>
                </div>

                @if($papers->count() > 0)
                    <div class="papers-list-horizontal">
                        @foreach($papers as $paper)
                            @php
                                $isSaved = Auth::check() && isset($savedPaperIds) && is_array($savedPaperIds) && in_array($paper->id, $savedPaperIds);
                            @endphp
                            <div class="paper-result-horizontal">
                                <div class="paper-result-content">
                                    <h3 class="paper-result-title-horizontal">
                                        <a href="{{ route('paper.view', $paper->id) }}" target="_blank">{{ $paper->title }}</a>
                                    </h3>
                                    
                                    <div class="paper-result-authors-horizontal">
                                        @foreach($paper->authors->take(3) as $index => $author)
                                            <span class="author-name-horizontal">{{ $author->author_name }}</span>@if($index < min(2, $paper->authors->count() - 1)), @endif
                                        @endforeach
                                        @if($paper->authors->count() > 3)
                                            <span class="more-authors">, et al.</span>
                                        @endif
                                        <span class="paper-source"> - {{ $paper->publication_year }}</span>
                                    </div>

                                    <div class="paper-result-abstract-horizontal">
                                        <p>{{ Str::limit($paper->abstract, 250) }}</p>
                                    </div>

                                    <div class="paper-result-actions-horizontal">
                                        @auth
                                            <button type="button" 
                                                    class="btn-save-paper {{ $isSaved ? 'saved' : '' }}" 
                                                    data-paper-id="{{ $paper->id }}"
                                                    onclick="toggleSavePaper({{ $paper->id }}, this)">
                                                {{ $isSaved ? '✓ Saved' : 'Save' }}
                                            </button>
                                        @endauth
                                        <a href="{{ route('paper.view', $paper->id) }}" class="btn-link-action">View Details</a>
                                        <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn-link-action">Download PDF</a>
                                    </div>
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

function toggleSavePaper(paperId, button) {
    fetch(`{{ route('search.save-paper', '') }}/${paperId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.saved) {
                button.textContent = '✓ Saved';
                button.classList.add('saved');
            } else {
                button.textContent = 'Save';
                button.classList.remove('saved');
            }
        } else {
            alert(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the paper');
    });
}
</script>
@endsection