<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReviewerController extends Controller
{
    public function articles(Request $request)
    {
        $query = $request->input('search', '');
        $statusFilter = $request->input('status', 'all');
        
        $papers = Paper::with(['authors', 'uploader'])
            ->orderBy('created_at', 'desc');

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

        return view('reviewer.articles', compact('papers', 'query', 'statusFilter', 'totalResults'));
    }

    public function review($id)
    {
        $paper = Paper::with(['authors', 'uploader', 'comments.user'])
                     ->where('id', $id)
                     ->firstOrFail();
        
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
}

