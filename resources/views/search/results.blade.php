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
                @if(session('visits'))
                    <p >You have visited this site {{ session('visits') }} times this session.</p>
                @endif
                <div class="search-container-page">
                    @include('components.search-form', [
                        'route' => 'search',
                        'query' => $query,
                        'placeholder' => 'Search research papers...',
                        'hiddenFields' => [
                            'category' => $categoryId ?? '',
                            'year' => $year ?? ''
                        ]
                    ])
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="search-results">
        <div class="container">
            <!-- Filters Section -->
            <div class="search-filters-section">
                @include('components.filters-form', [
                    'route' => 'search',
                    'query' => $query,
                    'categoryId' => $categoryId ?? '',
                    'year' => $year ?? '',
                    'categories' => $categories ?? collect(),
                    'availableYears' => $availableYears ?? []
                ])
            </div>

            @if(!empty($query) || !empty($categoryId) || !empty($year) || $papers->count() > 0)
                <div class="results-header">
                    <div class="results-info-section">
                        <h2>Search Results</h2>
                        <p class="results-info">
                            @if($totalResults > 0)
                                About {{ number_format($totalResults) }} result{{ $totalResults != 1 ? 's' : '' }} (0.09 sec)
                                @if(!empty($query))
                                    for "<strong>{{ $query }}</strong>"
                                @endif
                            @else
                                No results found
                                @if(!empty($query))
                                    for "<strong>{{ $query }}</strong>"
                                @endif
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
                            {{ $papers->appends([
                                'search' => $query,
                                'category' => $categoryId,
                                'year' => $year
                            ])->links() }}
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
@include('components.search-scripts', [
    'inputId' => 'searchInput',
    'includeSaveFunction' => true
])
@endsection