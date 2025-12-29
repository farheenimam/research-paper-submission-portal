@extends('layout')

@section('title', 'Admin Dashboard - Research Portal')
@section('description', 'Admin dashboard for managing users, papers, authors, and categories.')

@section('content')
<div class="admin-dashboard">
    <div class="container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <p>Welcome {{ Auth::user()->name }}, you have to approve the total count of unapproved papers: {{ $total_count }}</p>
            <p>Manage users, papers, authors, and categories</p>
        </div>

        <!-- Users Section -->
        <div id="users" class="admin-section">
            <div class="section-header">
                <h2>Users</h2>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Affiliation</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role->name ?? 'N/A') }}</td>
                                <td>{{ Str::limit($user->affiliation ?? 'N/A', 30) }}</td>
                                <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="actions-cell">
                                <a href="{{ route('admin.view-user', $user->id) }}"  class="btn btn-primary btn-sm">View Details</a>
                                    <form method="POST" action="{{ route('admin.delete-user', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Papers Section -->
        <div id="papers" class="admin-section">
            <div class="section-header">
                <h2>Papers</h2>
            </div>


            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Uploaded By</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Year</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($papers as $paper)
                            <tr>
                                <td>{{ $paper->id }}</td>
                                <td>{{ Str::limit($paper->title, 25) }}</td>
                                <td>{{ $paper->uploader->name ?? 'N/A' }}</td>
                                <td><span class="status-badge status-{{ $paper->status }}">{{ ucfirst($paper->status) }}</span></td>
                                <td>{{ $paper->categories->first()->name ?? 'N/A' }}</td>
                                <td>{{ $paper->publication_year }}</td>
                                <td>{{ $paper->created_at ? $paper->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="actions-cell">
                                    <a href="{{ route('admin.view-paper', $paper->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                    <form method="POST" action="{{ route('admin.delete-paper', $paper->id) }}" onsubmit="return confirm('Are you sure you want to delete this paper?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Authors Section -->
        <div id="authors" class="admin-section">
            <div class="section-header">
                <h2>Authors</h2>
            </div>


            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Author Name</th>
                            <th>Email</th>
                            <th>Affiliation</th>
                            <th>Paper</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authors as $author)
                            <tr>
                                <td>{{ $author->id }}</td>
                                <td>{{ $author->author_name }}</td>
                                <td>{{ $author->author_email ?? 'N/A' }}</td>
                                <td>{{ $author->affiliation ?? 'N/A' }}</td>
                                <td>{{ Str::limit($author->paper->title ?? 'N/A', 40) }}</td>
                                <td class="actions-cell">
                                    <form method="POST" action="{{ route('admin.delete-author', $author->id) }}" onsubmit="return confirm('Are you sure you want to delete this author?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categories Section -->
        <div id="categories" class="admin-section">
            <div class="section-header">
                <h2>Categories</h2>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->created_at ? $category->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="actions-cell">
                                    <form method="POST" action="{{ route('admin.delete-category', $category->id) }}" onsubmit="return confirm('Are you sure you want to delete this category?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Admin form toggle functions are now in common.js --}}
@endsection

@section('styles')
<link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

