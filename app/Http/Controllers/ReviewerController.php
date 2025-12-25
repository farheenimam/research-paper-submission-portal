<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReviewerController extends Controller
{
    public function articles(Request $request)
    {
        $query = $request->input('search', '');
        $statusFilter = $request->input('status', 'all');
        $categoryId = $request->input('category', '');
        $year = $request->input('year', '');
        
        // Get all categories for filter dropdown
        $categories = Category::orderBy('name', 'asc')->get();
        
        // Get available years for filter (from all papers)
        $availableYears = Paper::distinct()
            ->orderBy('publication_year', 'desc')
            ->pluck('publication_year')
            ->toArray();
        
        $papers = Paper::with(['authors', 'uploader', 'categories'])
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if (!empty($query)) {
            $papers = $papers->where('title', 'LIKE', "%{$query}%");
        }

        // Apply status filter
        if ($statusFilter !== 'all') {
            $papers = $papers->where('status', $statusFilter);
        }

        // Apply category filter
        if (!empty($categoryId)) {
            $papers = $papers->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Apply year filter
        if (!empty($year)) {
            $papers = $papers->where('publication_year', $year);
        }

        $papers = $papers->paginate(12);
        $totalResults = $papers->total();

        return view('reviewer.articles', compact('papers', 'query', 'statusFilter', 'totalResults', 'categories', 'availableYears', 'categoryId', 'year'));
    }

    public function review($id)
    {
        $paper = Paper::with([
            'authors' => function($query) {
                $query->orderBy('id', 'asc');
            },
            'uploader', 
            'comments.user'
        ])
        ->where('id', $id)
        ->firstOrFail();
        
        // Explicitly reload all authors for this paper_id from paper_authors table
        // The relationship automatically filters by paper_id
        $paper->load(['authors' => function($query) {
            $query->orderBy('id', 'asc');
        }]);
        
        return view('reviewer.review', compact('paper'));
    }

    public function updateReview(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'comment' => 'nullable|string|max:5000',
        ]);

        $paper = Paper::findOrFail($id);
        $user = Auth::user();

        // Update paper status
        $paper->status = $request->status;
        
        // If approved, set approved_by
        if ($request->status === 'approved') {
            $paper->approved_by = $user->id;
        } else {
            $paper->approved_by = null;
        }
        
        $paper->save();

        // Add comment if provided
        if (!empty($request->comment)) {
            Comment::create([
                'paper_id' => $paper->id,
                'user_id' => $user->id,
                'comment' => $request->comment,
            ]);
        }

        Session::flash('success', 'Review submitted successfully!');
        return redirect()->route('reviewer.articles');
    }

    public function history(Request $request)
    {
        $query = $request->input('search', '');
        $statusFilter = $request->input('status', 'all');
        $reviewerId = Auth::id();
        
        // Get papers that the reviewer has reviewed (has comments or approved)
        $reviewedPaperIds = Comment::where('user_id', $reviewerId)
            ->pluck('paper_id')
            ->unique()
            ->toArray();
        
        // Also include papers approved by this reviewer
        $approvedPaperIds = Paper::where('approved_by', $reviewerId)
            ->pluck('id')
            ->toArray();
        
        // Combine and get unique paper IDs
        $allReviewedIds = array_unique(array_merge($reviewedPaperIds, $approvedPaperIds));
        
        $papers = Paper::with(['authors', 'uploader', 'comments' => function($query) use ($reviewerId) {
            $query->where('user_id', $reviewerId);
        }]);
        
        if (!empty($allReviewedIds)) {
            $papers = $papers->whereIn('id', $allReviewedIds);
        } else {
            // Return empty result if no reviewed papers
            $papers = $papers->whereRaw('1 = 0');
        }
        
        $papers = $papers->orderBy('updated_at', 'desc');
        
        // Apply search filter
        if (!empty($query)) {
            $papers = $papers->where('title', 'LIKE', "%{$query}%");
        }
        
        // Apply status filter
        if ($statusFilter !== 'all') {
            $papers = $papers->where('status', $statusFilter);
        }
        
        $papers = $papers->paginate(12);
        $totalResults = $papers->total();
        
        return view('reviewer.history', compact('papers', 'query', 'statusFilter', 'totalResults'));
    }
}

