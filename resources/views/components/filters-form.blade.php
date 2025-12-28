{{-- 
    Reusable Filters Form Component (Category & Year)
    
    Usage:
    @include('components.filters-form', [
        'route' => 'search', // Required: The route name for the form action
        'query' => $query ?? '', // Optional: Current search query value
        'categoryId' => $categoryId ?? '', // Optional: Currently selected category ID
        'year' => $year ?? '', // Optional: Currently selected year
        'categories' => $categories ?? collect(), // Optional: Collection of categories
        'availableYears' => $availableYears ?? [], // Optional: Array of available years
        'additionalHiddenFields' => [] // Optional: Array of additional hidden fields
    ])
--}}

@php
    $route = $route ?? '';
    $query = $query ?? '';
    $categoryId = $categoryId ?? '';
    $year = $year ?? '';
    $categories = $categories ?? collect();
    $availableYears = $availableYears ?? [];
    $additionalHiddenFields = $additionalHiddenFields ?? [];
@endphp

<div class="search-filters-section">
    <form class="filters-form" action="{{ route($route) }}" method="GET">
        <input type="hidden" name="search" value="{{ $query }}">
        @foreach($additionalHiddenFields as $name => $value)
            @if(!empty($value))
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endif
        @endforeach
        
        <div class="filters-grid">
            <div class="filter-group">
                <label for="category" class="filter-label">Category</label>
                <select name="category" id="category" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="year" class="filter-label">Publication Year</label>
                <select name="year" id="year" class="filter-select">
                    <option value="">All Years</option>
                    @foreach($availableYears as $availableYear)
                        <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter-apply">Apply Filters</button>
                <a href="{{ route($route) }}{{ !empty($query) ? '?search=' . urlencode($query) : '' }}" class="btn-filter-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

