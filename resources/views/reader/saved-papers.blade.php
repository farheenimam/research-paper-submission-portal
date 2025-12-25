@extends('layout')

@section('title', 'Saved Papers - Research Portal')
@section('description', 'View your saved research papers.')

@section('content')
<div class="search-page">
    <!-- Search Header -->
    <div class="search-header">
        <div class="container">
            <div class="search-header-content">
                <h1>Saved Papers</h1>
                <div class="search-container-page">
                    <form class="search-form-page" action="{{ route('reader.saved-papers') }}" method="GET">
                        <input type="text" 
                               class="search-input-page" 
                               name="search"
                               value="{{ $query }}"
                               placeholder="Search saved papers..."
                               autocomplete="off"
                               id="searchInput"
                               maxlength="255"
                               pattern=".{0,255}"
                               title="Search query must not exceed 255 characters">
                        <button type="submit" class="search-btn-page">SEARCH</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Saved Papers Results -->
    <div class="search-results">
        <div class="container">
            <div class="results-header">
                <div class="results-info-section">
                    <h2>My Saved Papers</h2>
                    <p class="results-info">
                        @if($totalResults > 0)
                            Found {{ number_format($totalResults) }} saved paper{{ $totalResults != 1 ? 's' : '' }}
                            @if(!empty($query))
                                for "<strong>{{ $query }}</strong>"
                            @endif
                        @else
                            @if(!empty($query))
                                No results found for "<strong>{{ $query }}</strong>"
                            @else
                                You haven't saved any papers yet
                            @endif
                        @endif
                    </p>
                </div>
            </div>

            @if($papers->count() > 0)
                <div class="papers-list-horizontal">
                    @foreach($papers as $paper)
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
                                    <button type="button" 
                                            class="btn-save-paper saved" 
                                            data-paper-id="{{ $paper->id }}"
                                            onclick="toggleSavePaper({{ $paper->id }}, this)">
                                        ✓ Saved
                                    </button>
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
                    <div class="no-results-icon">📄</div>
                    <h3>No saved papers</h3>
                    @if(!empty($query))
                        <p>Try different keywords or check your spelling</p>
                    @else
                        <p>Start saving papers from search results to view them here</p>
                        <a href="{{ route('search') }}" class="btn btn-primary" style="margin-top: 20px;">Search Papers</a>
                    @endif
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
    
    if (searchInput) {
        searchInput.focus();
        const length = searchInput.value.length;
        searchInput.setSelectionRange(length, length);
    }
});

function toggleSavePaper(paperId, button) {
    fetch(`/search/save/${paperId}`, {
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
                // If on saved papers page, remove the paper from view
                if (window.location.pathname.includes('saved-papers')) {
                    button.closest('.paper-result-horizontal').remove();
                }
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

