{{-- 
    Reusable Search Form Component
    
    Usage:
    @include('components.search-form', [
        'route' => 'search',  // Route name
        'query' => $query,     // Current search query
        'placeholder' => 'Search research papers...',  // Optional
        'hiddenFields' => ['category' => $categoryId, 'year' => $year]  // Optional
    ])
--}}

@php
    $placeholder = $placeholder ?? 'Search research papers...';
    $hiddenFields = $hiddenFields ?? [];
    $formClass = $formClass ?? 'search-form-page';
    $inputClass = $inputClass ?? 'search-input-page';
    $buttonClass = $buttonClass ?? 'search-btn-page';
    $inputId = $inputId ?? 'searchInput';
@endphp

<form class="{{ $formClass }}" action="{{ route($route) }}" method="GET">
    <input type="text" 
           class="{{ $inputClass }}" 
           name="search"
           value="{{ $query ?? '' }}"
           placeholder="{{ $placeholder }}"
           autocomplete="off"
           id="{{ $inputId }}"
           maxlength="255"
           pattern=".{0,255}"
           title="Search query must not exceed 255 characters">
    
    @foreach($hiddenFields as $fieldName => $fieldValue)
        @if(!empty($fieldValue))
            <input type="hidden" name="{{ $fieldName }}" value="{{ $fieldValue }}">
        @endif
    @endforeach
    
    <button type="submit" class="{{ $buttonClass }}">SEARCH</button>
</form>

