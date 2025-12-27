{{-- 
    Reusable Search Scripts Component
    
    Usage:
    @include('components.search-scripts', [
        'inputId' => 'searchInput',  // Optional, default: 'searchInput'
        'includeSaveFunction' => true  // Optional, include toggleSavePaper function
    ])
--}}

@php
    $inputId = $inputId ?? 'searchInput';
    $includeSaveFunction = $includeSaveFunction ?? false;
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('{{ $inputId }}');
    
    if (searchInput) {
        searchInput.focus();
        const length = searchInput.value.length;
        searchInput.setSelectionRange(length, length);
    }
});

{{-- toggleSavePaper function is now in common.js --}}
</script>

