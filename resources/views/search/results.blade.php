{{-- @extends('layout') - Extends the main layout template --}}
{{-- This page will use layout.blade.php as base and inject content into @yield sections --}}
@extends('layout')

{{-- @section('title', ...) - Sets page title that appears in browser tab --}}
@section('title', 'Search Results - Research Portal')
{{-- @section('description', ...) - Sets meta description for SEO --}}
@section('description', 'Search results for academic research papers on Research Portal.')

@section('content')
<div class="search-page">
    <!-- Search Header -->
    <div class="search-header">
        <div class="container">
            <div class="search-header-content">
                <h1>Research Portal</h1>
                @if(session('visits'))
                    <p>You have visited this site {{ session('visits') }} times this session.</p>
                @endif

                <div class="search-container-page">
                    {{-- @include('components.search-form', ...) - Includes reusable search form component --}}
                    {{-- Passes data to component: route name, current query, placeholder text, and hidden fields --}}
                    {{-- $categoryId ?? '' - Uses categoryId if exists, otherwise empty string (null coalescing operator) --}}
                    @include('components.search-form', [
                        'route' => 'search',  // Route name for form submission
                        'query' => $query,     // Current search query from controller
                        'placeholder' => 'Search research papers...',
                        'hiddenFields' => [    // Hidden fields to preserve filters when searching
                            'category' => $categoryId ?? '',  // Current category filter
                            'year' => $year ?? ''             // Current year filter
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
                {{-- @include('components.filters-form', ...) - Includes filter form component --}}
                {{-- This component shows category and year filter dropdowns --}}
                {{-- $categories ?? collect() - Uses categories if exists, otherwise empty collection --}}
                {{-- $availableYears ?? [] - Uses availableYears if exists, otherwise empty array --}}
                @include('components.filters-form', [
                    'route' => 'search',              // Form submission route
                    'query' => $query,                // Current search query
                    'categoryId' => $categoryId ?? '', // Currently selected category
                    'year' => $year ?? '',            // Currently selected year
                    'categories' => $categories ?? collect(),  // All categories for dropdown
                    'availableYears' => $availableYears ?? []  // Available years for dropdown
                ])
            </div>

            {{-- @if(!empty($query) || ...) - Check if user has searched or filters applied --}}
            {{-- Shows results section only if search/filter was performed OR papers exist --}}
            @if(!empty($query) || !empty($categoryId) || !empty($year) || $papers->count() > 0)
                <div class="results-header">
                    <div class="results-info-section">
                        <h2>Search Results</h2>
                        <p class="results-info">
                            {{-- @if($totalResults > 0) - Check if results found --}}
                            @if($totalResults > 0)
                                {{-6. Session Timeout / Auto-Logout Warning

Track login time in session:

session(['login_time' => now()]);


Show warning if user has been idle for X minutes.

help me implement this- number_format($totalResults) - Formats number with commas (e.g., 1,234) --}}
                                {{-- $totalResults != 1 ? 's' : '' - Adds 's' for plural (1 result vs 2 results) --}}
                                About {{ number_format($totalResults) }} result
                                {{-- Show search query if user searched --}}
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

                {{-- @if($papers->count() > 0) - Check if papers collection has any items --}}
                @if($papers->count() > 0)
                    <div class="papers-list-horizontal">
                        {{-- @foreach($papers as $paper) - Loop through each paper in collection --}}
                        @foreach($papers as $paper)
                            {{-- Check if current paper is saved by logged-in user --}}
                            {{-- Auth::check() - Check if user is logged in --}}
                            {{-- in_array($paper->id, $savedPaperIds) - Check if paper ID exists in saved papers array --}}
                            @php
                                $isSaved = Auth::check() && isset($savedPaperIds) && is_array($savedPaperIds) && in_array($paper->id, $savedPaperIds);
                            @endphp
                            <div class="paper-result-horizontal">
                                <div class="paper-result-content">
                                    <h3 class="paper-result-title-horizontal">
                                        {{-- route('paper.view', $paper->id) - Generates URL for paper detail page --}}
                                        {{-- target="_blank" - Opens link in new tab --}}
                                        <a href="{{ route('paper.view', $paper->id) }}" target="_blank">{{ $paper->title }}</a>
                                    </h3>
                                    
                                    <div class="paper-result-authors-horizontal">
                                        {{-- $paper->authors->take(3) - Get only first 3 authors --}}
                                        {{-- @foreach with $index - Loop counter (0, 1, 2, ...) --}}
                                        @foreach($paper->authors->take(3) as $index => $author)
                                            <span class="author-name-horizontal">{{ $author->author_name }}</span>
                                            {{-- Add comma after author name (except last one) --}}
                                            {{-- min(2, $paper->authors->count() - 1) - Ensures comma logic works correctly --}}
                                            @if($index < min(2, $paper->authors->count() - 1)), @endif
                                        @endforeach
                                        {{-- If more than 3 authors, show "et al." (and others) --}}
                                        @if($paper->authors->count() > 3)
                                            <span class="more-authors">, et al.</span>
                                        @endif
                                        <span class="paper-source"> - {{ $paper->publication_year }}</span>
                                    </div>

                                    <div class="paper-result-abstract-horizontal">
                                        {{-- Str::limit($paper->abstract, 250) - Truncates abstract to 250 characters --}}
                                        {{-- Adds "..." automatically if text is longer --}}
                                        <p>{{ Str::limit($paper->abstract, 250) }}</p>
                                    </div>

                                    <div class="paper-result-actions-horizontal">
                                        {{-- @auth ... @endauth - Only show if user is logged in --}}
                                        @auth
                                            {{-- Save/Unsave button - Toggles paper save status --}}
                                            {{-- onclick="toggleSavePaper(...)" - JavaScript function to save/unsave --}}
                                            {{-- $isSaved ? 'saved' : '' - Adds 'saved' class if paper is already saved --}}
                                            <button type="button" 
                                                    class="btn-save-paper {{ $isSaved ? 'saved' : '' }}" 
                                                    data-paper-id="{{ $paper->id }}"
                                                    onclick="toggleSavePaper({{ $paper->id }}, this)">
                                                {{-- Ternary operator: Show "✓ Saved" if saved, otherwise "Save" --}}
                                                {{ $isSaved ? '✓ Saved' : 'Save' }}
                                            </button>
                                        @endauth
                                        {{-- route('paper.view', $paper->id) - Link to paper detail page --}}
                                        <a href="{{ route('paper.view', $paper->id) }}" class="btn-link-action">View Details</a>
                                        {{-- asset($paper->pdf_path) - Generates URL for PDF file --}}
                                        {{-- target="_blank" - Opens PDF in new tab --}}
                                        <a href="{{ asset($paper->pdf_path) }}" target="_blank" class="btn-link-action">Download PDF</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    {{-- @if($papers->hasPages()) - Check if pagination is needed (more than 1 page) --}}
                    @if($papers->hasPages())
                        <div class="pagination-wrapper">
                            {{-- $papers->appends([...]) - Adds query parameters to pagination links --}}
                            {{-- ->links() - Generates pagination HTML (Previous, 1, 2, 3, Next buttons) --}}
                            {{-- This preserves search query, category, and year when user clicks page numbers --}}
                            {{ $papers->appends([
                                'search' => $query,      // Preserve search query
                                'category' => $categoryId, // Preserve category filter
                                'year' => $year          // Preserve year filter
                            ])->links() }}
                        </div>
                    @endif
                @else
                    {{-- No results found - Show helpful message --}}
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
                {{-- Initial state - No search performed yet --}}
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
{{-- asset() - Generates URL for CSS file in public folder --}}
{{-- asset('css/search.css') => http://site.com/css/search.css --}}
<link href="{{ asset('css/search.css') }}" rel="stylesheet">
@endsection

@section('scripts')
{{-- @include('components.search-scripts', ...) - Includes JavaScript component --}}
{{-- 'includeSaveFunction' => true - Includes toggleSavePaper function for save/unsave functionality --}}
@include('components.search-scripts', [
    'inputId' => 'searchInput',        // ID of search input field
    'includeSaveFunction' => true      // Include save paper JavaScript function
])
@endsection