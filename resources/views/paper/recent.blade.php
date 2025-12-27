@extends('layout')

@section('title', 'Recent Research Work - Research Portal')
@section('description', 'Browse recent published research papers on Research Portal.')

@section('content')
<div class="recent-research-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Recent Research Work</h1>
            <p>Browse the latest published research papers</p>
        </div>

        <!-- Search Bar -->
        <div class="search-section">
            @include('components.search-form', [
                'route' => 'paper.recent',
                'query' => $query,
                'placeholder' => 'Search research papers...',
                'formClass' => 'search-form-recent',
                'inputClass' => 'search-input-recent',
                'buttonClass' => 'search-btn-recent',
                'inputId' => 'searchInputRecent',
                'hiddenFields' => [
                    'category' => $categoryId ?? '',
                    'year' => $year ?? ''
                ]
            ])
        </div>

        <!-- Filters Section -->
        <div class="search-filters-section">
            @include('components.filters-form', [
                'route' => 'paper.recent',
                'query' => $query,
                'categoryId' => $categoryId ?? '',
                'year' => $year ?? '',
                'categories' => $categories ?? collect(),
                'availableYears' => $availableYears ?? []
            ])
        </div>

        <!-- Results Info -->
        @if(!empty($query) || !empty($categoryId) || !empty($year))
            <div class="results-info">
                <p>
                    @if($totalResults > 0)
                        Found {{ number_format($totalResults) }} result{{ $totalResults != 1 ? 's' : '' }}
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
        @endif

        <!-- Papers Grid -->
        @if($papers->count() > 0)
            <div class="papers-grid-recent">
                @foreach($papers as $paper)
                    <div class="paper-card-recent">
                        <div class="paper-card-header">
                            <h3 class="paper-title-recent">
                                <a href="{{ route('paper.view', $paper->id) }}">{{ $paper->title }}</a>
                            </h3>
                            <div class="paper-meta-recent">
                                <span class="publication-year">📅 {{ $paper->publication_year }}</span>
                                <span class="separator">•</span>
                                <span class="authors-count">👥 {{ $paper->authors->count() }} author{{ $paper->authors->count() != 1 ? 's' : '' }}</span>
                            </div>
                        </div>

                        <div class="paper-authors-recent">
                            <strong>Authors:</strong>
                            @foreach($paper->authors as $index => $author)
                                <span class="author-name">{{ $author->author_name }}</span>@if($index < $paper->authors->count() - 1), @endif
                            @endforeach
                        </div>

                        <div class="paper-abstract-recent">
                            <p>{{ Str::limit($paper->abstract, 200) }}</p>
                        </div>

                        <div class="paper-actions-recent">
                            <a href="{{ route('paper.view', $paper->id) }}" class="btn btn-outline btn-sm">View Details</a>
                            <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn btn-primary btn-sm">Download PDF</a>
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
                <div class="no-results-icon">📄</div>
                <h3>No papers found</h3>
                @if(!empty($query))
                    <p>Try different keywords or check your spelling</p>
                @else
                    <p>No research papers have been published yet</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/recent.css') }}" rel="stylesheet">
@endsection

