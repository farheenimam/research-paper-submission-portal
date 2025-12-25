@extends('layout')

@section('title', 'Articles - Research Portal')
@section('description', 'Review and manage research papers as a reviewer.')

@section('content')
<div class="articles-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Articles</h1>
            <p>Review and manage research papers</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <form class="search-filter-form" action="{{ route('reviewer.articles') }}" method="GET">
                <div class="search-wrapper">
                    <input type="text" 
                           class="search-input-articles" 
                           name="search"
                           value="{{ $query }}"
                           placeholder="Search research papers..."
                           autocomplete="off"
                           maxlength="255"
                           pattern=".{0,255}"
                           title="Search query must not exceed 255 characters">
                    <button type="submit" class="search-btn-articles">SEARCH</button>
                </div>
                
                <div class="filter-wrapper">
                    <label for="status-filter" class="filter-label">Filter by Status:</label>
                    <select name="status" id="status-filter" class="status-filter">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Papers</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @if($query)
                        <input type="hidden" name="search" value="{{ $query }}">
                    @endif
                </div>
            </form>
        </div>

        <!-- Additional Filters Section -->
        <div class="search-filters-section">
            <form class="filters-form" action="{{ route('reviewer.articles') }}" method="GET">
                <input type="hidden" name="search" value="{{ $query }}">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="category" class="filter-label">Category</label>
                        <select name="category" id="category" class="filter-select">
                            <option value="">All Categories</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="year" class="filter-label">Publication Year</label>
                        <select name="year" id="year" class="filter-select">
                            <option value="">All Years</option>
                            @if(isset($availableYears))
                                @foreach($availableYears as $availableYear)
                                    <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                        {{ $availableYear }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter-apply">Apply Filters</button>
                        <a href="{{ route('reviewer.articles') }}{{ !empty($query) || $statusFilter !== 'all' ? '?' . http_build_query(array_filter(['search' => $query, 'status' => $statusFilter !== 'all' ? $statusFilter : null])) : '' }}" class="btn-filter-clear">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Info -->
        @if(!empty($query) || $statusFilter !== 'all' || !empty($categoryId) || !empty($year))
            <div class="results-info">
                <p>
                    @if($totalResults > 0)
                        Found {{ number_format($totalResults) }} result{{ $totalResults != 1 ? 's' : '' }}
                        @if(!empty($query))
                            for "<strong>{{ $query }}</strong>"
                        @endif
                        @if($statusFilter !== 'all')
                            with status: <strong>{{ ucfirst($statusFilter) }}</strong>
                        @endif
                    @else
                        No results found
                        @if(!empty($query))
                            for "<strong>{{ $query }}</strong>"
                        @endif
                        @if($statusFilter !== 'all')
                            with status: <strong>{{ ucfirst($statusFilter) }}</strong>
                        @endif
                    @endif
                </p>
            </div>
        @endif

        <!-- Papers Grid -->
        @if($papers->count() > 0)
            <div class="papers-grid-articles">
                @foreach($papers as $paper)
                    <div class="paper-card-articles">
                        <div class="paper-status-articles status-{{ $paper->status }}">
                            {{ ucfirst($paper->status) }}
                        </div>
                        
                        <div class="paper-card-header-articles">
                            <h3 class="paper-title-articles">
                                <a href="{{ route('paper.view', $paper->id) }}">{{ $paper->title }}</a>
                            </h3>
                            <div class="paper-meta-articles">
                                <span class="publication-year">📅 {{ $paper->publication_year }}</span>
                                <span class="separator">•</span>
                                <span class="authors-count">👥 {{ $paper->authors->count() }} author{{ $paper->authors->count() != 1 ? 's' : '' }}</span>
                            </div>
                        </div>

                        <div class="paper-authors-articles">
                            <strong>Authors:</strong>
                            @foreach($paper->authors as $index => $author)
                                <span class="author-name">{{ $author->author_name }}</span>@if($index < $paper->authors->count() - 1), @endif
                            @endforeach
                        </div>

                        <div class="paper-abstract-articles">
                            <p>{{ Str::limit($paper->abstract, 200) }}</p>
                        </div>

                        <div class="paper-uploader-articles">
                            <strong>Uploaded by:</strong> {{ $paper->uploader->name ?? 'Unknown' }}
                            <span class="upload-date">📤 {{ $paper->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="paper-actions-articles">
                            <a href="{{ route('reviewer.review', $paper->id) }}" class="btn btn-outline btn-sm">Review</a>
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
                        'status' => $statusFilter,
                        'category' => $categoryId,
                        'year' => $year
                    ])->links() }}
                </div>
            @endif
        @else
            <div class="no-results">
                <div class="no-results-icon">📄</div>
                <h3>No papers found</h3>
                <p>Try adjusting your search or filter criteria</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/articles.css') }}" rel="stylesheet">
@endsection

