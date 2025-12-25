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

    public function storeUser(Request $request)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'affiliation' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'affiliation' => $request->affiliation,
        ]);

        Session::flash('success', 'User created successfully!');
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

    public function storePaper(Request $request)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'uploaded_by' => 'required|exists:users,id',
            'status' => 'required|in:pending,approved,rejected',
            'author_name' => 'required|string|max:150',
            'author_email' => 'nullable|email|max:150',
            'author_affiliation' => 'nullable|string|max:255',
        ]);

        // Handle PDF upload
        $pdfFile = $request->file('pdf_file');
        $filename = time() . '_' . $pdfFile->getClientOriginalName();
        $pdfPath = 'papers/' . $filename;
        $pdfFile->move(public_path('papers'), $filename);

        // Create paper record
        $paper = Paper::create([
            'title' => $request->title,
            'abstract' => $request->abstract,
            'pdf_path' => $pdfPath,
            'publication_year' => $request->publication_year,
            'status' => $request->status,
            'uploaded_by' => $request->uploaded_by,
        ]);

        // Attach category to paper
        $paper->categories()->attach($request->category_id);

        // Create author
        PaperAuthor::create([
            'paper_id' => $paper->id,
            'user_id' => $request->uploaded_by,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'affiliation' => $request->author_affiliation,
        ]);

        Session::flash('success', 'Paper created successfully!');
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

    public function storeAuthor(Request $request)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'paper_id' => 'required|exists:papers,id',
            'author_name' => 'required|string|max:150',
            'author_email' => 'nullable|email|max:150',
            'affiliation' => 'nullable|string|max:255',
        ]);

        PaperAuthor::create([
            'paper_id' => $request->paper_id,
            'user_id' => null,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'affiliation' => $request->affiliation,
        ]);

        Session::flash('success', 'Author created successfully!');
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

    public function storeCategory(Request $request)
    {
        if (Auth::check() && Auth::user()->email !== 'farheenimam@gmail.com') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        Session::flash('success', 'Category created successfully!');
        return redirect()->route('admin.dashboard');
    }
}
