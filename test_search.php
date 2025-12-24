<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Paper;

echo "Testing Simplified Search Functionality\n";
echo "======================================\n\n";

// Test 1: Check if we have approved papers
$approvedPapers = Paper::where('status', 'approved')->with('authors')->get();
echo "Approved papers count: " . $approvedPapers->count() . "\n";

if ($approvedPapers->count() > 0) {
    echo "\nApproved Papers:\n";
    foreach ($approvedPapers as $paper) {
        echo "- ID: {$paper->id}, Title: '{$paper->title}', Status: {$paper->status}\n";
    }
}

// Test 2: Test simple search by title
echo "\n\nTesting search for 'intelligence' in title:\n";
$searchResults = Paper::where('status', 'approved')
    ->where('title', 'LIKE', '%intelligence%')
    ->with(['authors', 'uploader'])
    ->get();

echo "Search results count: " . $searchResults->count() . "\n";

if ($searchResults->count() > 0) {
    foreach ($searchResults as $paper) {
        echo "- Found: '{$paper->title}'\n";
    }
} else {
    echo "No results found for 'intelligence'\n";
}

// Test 3: Test search for 'artificial'
echo "\n\nTesting search for 'artificial' in title:\n";
$searchResults2 = Paper::where('status', 'approved')
    ->where('title', 'LIKE', '%artificial%')
    ->with(['authors', 'uploader'])
    ->get();

echo "Search results count: " . $searchResults2->count() . "\n";

if ($searchResults2->count() > 0) {
    foreach ($searchResults2 as $paper) {
        echo "- Found: '{$paper->title}'\n";
    }
} else {
    echo "No results found for 'artificial'\n";
}

// Test 4: Test case insensitive search
echo "\n\nTesting case insensitive search for 'ARTIFICIAL':\n";
$searchResults3 = Paper::where('status', 'approved')
    ->where('title', 'LIKE', '%ARTIFICIAL%')
    ->with(['authors', 'uploader'])
    ->get();

echo "Search results count: " . $searchResults3->count() . "\n";

if ($searchResults3->count() > 0) {
    foreach ($searchResults3 as $paper) {
        echo "- Found: '{$paper->title}'\n";
    }
} else {
    echo "No results found for 'ARTIFICIAL'\n";
}

echo "\nTest completed!\n";