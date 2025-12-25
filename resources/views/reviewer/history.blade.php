@extends('layout')

@section('title', 'My Review History - Research Portal')
@section('description', 'View all papers you have reviewed.')

@section('content')
<div class="articles-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>My Review History</h1>
            <p>Papers you have reviewed</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <form class="search-filter-form" action="{{ route('reviewer.history') }}" method="GET">
                <div class="search-wrapper">
                    <input type="text" 
                           class="search-input-articles" 
                           name="search"
                           value="{{ $query }}"
                           placeholder="Search reviewed papers..."
                           autocomplete="off">
                    <button type="submit" class="search-btn-articles">SEARCH</button>
                </div>
                
                <div class="filter-wrapper">
                    <label for="status-filter" class="filter-label">Filter by Status:</label>
                    <select name="status" id="status-filter" class="status-filter" onchange="this.form.submit()">
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

        <!-- Results Info -->
        @if(!empty($query) || $statusFilter !== 'all')
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

                        @if($paper->comments->count() > 0)
                            <div class="review-info">
                                <strong>📝 Your Review:</strong>
                                <span class="review-date">Reviewed on {{ $paper->comments->first()->created_at->format('M d, Y') }}</span>
                                @if($paper->comments->count() > 1)
                                    <span class="review-count">({{ $paper->comments->count() }} comments)</span>
                                @endif
                            </div>
                        @endif

                        @if($paper->approved_by == Auth::id())
                            <div class="approval-info">
                                <strong>✅ Approved by you</strong>
                            </div>
                        @endif

                        <div class="paper-actions-articles">
                            <a href="{{ route('reviewer.review', $paper->id) }}" class="btn btn-outline btn-sm">View Review</a>
                            <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn btn-primary btn-sm">Download PDF</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($papers->hasPages())
                <div class="pagination-wrapper">
                    {{ $papers->appends(['search' => $query, 'status' => $statusFilter])->links() }}
                </div>
            @endif
        @else
            <div class="no-results">
                <div class="no-results-icon">📄</div>
                <h3>No reviewed papers found</h3>
                <p>You haven't reviewed any papers yet. <a href="{{ route('reviewer.articles') }}">Start reviewing papers</a></p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/articles.css') }}" rel="stylesheet">
<style>
.review-info {
    margin: 15px 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
    border-left: 3px solid #2d5016;
}

.review-info strong {
    color: #2d5016;
    display: block;
    margin-bottom: 5px;
}

.review-date {
    color: #666666;
    font-size: 14px;
    display: block;
}

.review-count {
    color: #2d5016;
    font-size: 12px;
    font-weight: 600;
    margin-left: 5px;
}

.approval-info {
    margin: 10px 0;
    padding: 8px;
    background-color: #d4edda;
    border-radius: 5px;
    color: #155724;
    font-size: 14px;
    text-align: center;
}
</style>
@endsection

