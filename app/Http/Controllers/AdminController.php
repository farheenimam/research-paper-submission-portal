<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paper;
use App\Models\PaperAuthor;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if user is admin (hardcoded email check)
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $users = User::with('role')->orderBy('created_at', 'desc')->get();
        $papers = Paper::with(['uploader', 'categories'])->orderBy('created_at', 'desc')->get();
        $authors = PaperAuthor::with(['paper', 'user'])->orderBy('id', 'desc')->get();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.dashboard', compact('users', 'papers', 'authors', 'categories'));
    }

    // User Management
    public function deleteUser($id)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $user = User::findOrFail($id);
        $user->delete();

        Session::flash('success', 'User deleted successfully!');
        return redirect()->route('admin.dashboard');
    }

    // Paper Management
    public function deletePaper($id)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $paper = Paper::findOrFail($id);
        
        // Delete PDF file if exists
        if ($paper->pdf_path && file_exists(public_path($paper->pdf_path))) {
            unlink(public_path($paper->pdf_path));
        }
        
        $paper->delete();

        Session::flash('success', 'Paper deleted successfully!');
        return redirect()->route('admin.dashboard');
    }

    // Author Management
    public function deleteAuthor($id)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $author = PaperAuthor::findOrFail($id);
        $author->delete();

        Session::flash('success', 'Author deleted successfully!');
        return redirect()->route('admin.dashboard');
    }

    // Category Management
    public function deleteCategory($id)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $category = Category::findOrFail($id);
        $category->delete();

        Session::flash('success', 'Category deleted successfully!');
        return redirect()->route('admin.dashboard');
    }

    // Paper Review Management (Admin can approve/reject papers)
    public function viewPaper($id)
    {
        // Check if user is admin
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $paper = Paper::with([
            'authors' => function($query) {
                $query->orderBy('id', 'asc');
            },
            'uploader', 
            'categories'
        ])
        ->where('id', $id)
        ->firstOrFail();
        
        return view('admin.view-paper', compact('paper'));
    }

    public function approvePaper(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $paper = Paper::findOrFail($id);
        $user = Auth::user();

        // Update paper status to approved
        $paper->status = 'approved';
        $paper->approved_by = $user->id;
        $paper->save();

        Session::flash('success', 'Paper approved successfully!');
        return redirect()->route('admin.dashboard');
    }

    public function rejectPaper(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $paper = Paper::findOrFail($id);
        $user = Auth::user();

        // Update paper status to rejected
        $paper->status = 'rejected';
        $paper->approved_by = $user->id;
        $paper->save();

        Session::flash('success', 'Paper rejected successfully!');
        return redirect()->route('admin.dashboard');
    }
}
