@extends('layout')

@section('title', 'Admin Dashboard - Research Portal')
@section('description', 'Admin dashboard for managing users, papers, authors, and categories.')

@section('content')
<div class="admin-dashboard">
    <div class="container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <p>Manage users, papers, authors, and categories</p>
        </div>

        <!-- Users Section -->
        <div id="users" class="admin-section">
            <div class="section-header">
                <h2>Users</h2>
                <button type="button" class="btn btn-primary" onclick="adminForms.toggleAdd('user')">Add User</button>
            </div>

            <!-- Add User Form -->
            <div id="add-user-form" class="add-form" style="display: none;">
                <form method="POST" action="{{ route('admin.store-user') }}" class="admin-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control" 
                                   required 
                                   minlength="2"
                                   maxlength="150"
                                   pattern="[A-Za-z\s]{2,}"
                                   title="Name must be at least 2 characters and contain only letters and spaces">
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" 
                                   required 
                                   maxlength="150"
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   title="Please enter a valid email address">
                        </div>
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" 
                                   required 
                                   minlength="6"
                                   maxlength="255"
                                   pattern=".{6,}"
                                   title="Password must be at least 6 characters long">
                        </div>
                        <div class="form-group">
                            <label>Role *</label>
                            <select name="role_id" class="form-control" required>
                                <option value="">Select Role</option>
                                @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Affiliation</label>
                            <input type="text" name="affiliation" class="form-control"
                                   maxlength="255"
                                   pattern=".{0,255}"
                                   title="Affiliation must not exceed 255 characters">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleAdd('user')">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Update User Forms (hidden by default) -->
            @foreach($users as $user)
            <div id="edit-user-form-{{ $user->id }}" class="edit-form" style="display: none;">
                <form method="POST" action="{{ route('admin.update-user', $user->id) }}" class="admin-form">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" 
                                   required 
                                   minlength="2"
                                   maxlength="150"
                                   pattern="[A-Za-z\s]{2,}"
                                   title="Name must be at least 2 characters and contain only letters and spaces">
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" 
                                   required 
                                   maxlength="150"
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   title="Please enter a valid email address">
                        </div>
                        <div class="form-group">
                            <label>Password (Leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control" 
                                   minlength="6"
                                   maxlength="255"
                                   pattern=".{6,}"
                                   title="Password must be at least 6 characters long">
                        </div>
                        <div class="form-group">
                            <label>Role *</label>
                            <select name="role_id" class="form-control" required>
                                @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Affiliation</label>
                            <input type="text" name="affiliation" class="form-control" value="{{ $user->affiliation }}"
                                   maxlength="255"
                                   pattern=".{0,255}"
                                   title="Affiliation must not exceed 255 characters">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleEdit('user', {{ $user->id }})">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            @endforeach

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
                                <td>{{ $user->affiliation ?? 'N/A' }}</td>
                                <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="actions-cell">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="adminForms.toggleEdit('user', {{ $user->id }})">Update</button>
                                    <form method="POST" action="{{ route('admin.delete-user', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                <button type="button" class="btn btn-primary" onclick="adminForms.toggleAdd('paper')">Add Paper</button>
            </div>

            <!-- Add Paper Form -->
            <div id="add-paper-form" class="add-form" style="display: none;">
                <form method="POST" action="{{ route('admin.store-paper') }}" enctype="multipart/form-data" class="admin-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control" 
                                   required 
                                   minlength="5"
                                   maxlength="255"
                                   pattern=".{5,255}"
                                   title="Title must be between 5 and 255 characters">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Abstract *</label>
                            <textarea name="abstract" class="form-control" rows="4" 
                                      required
                                      minlength="50"
                                      title="Abstract must be at least 50 characters long"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Publication Year *</label>
                            <input type="number" name="publication_year" class="form-control" 
                                   min="1900" 
                                   max="{{ date('Y') + 1 }}" 
                                   value="{{ date('Y') }}" 
                                   required
                                   step="1"
                                   title="Publication year must be between 1900 and {{ date('Y') + 1 }}">
                        </div>
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Uploaded By *</label>
                            <select name="uploaded_by" class="form-control" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>PDF File *</label>
                            <input type="file" name="pdf_file" class="form-control" 
                                   accept=".pdf,application/pdf" 
                                   required
                                   title="Please upload a PDF file (max 10MB)">
                        </div>
                        <div class="form-group">
                            <label>Author Name *</label>
                            <input type="text" name="author_name" class="form-control" 
                                   required 
                                   minlength="2"
                                   maxlength="150"
                                   pattern="[A-Za-z\s]{2,}"
                                   title="Author name must be at least 2 characters and contain only letters and spaces">
                        </div>
                        <div class="form-group">
                            <label>Author Email</label>
                            <input type="email" name="author_email" class="form-control"
                                   maxlength="150"
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   title="Please enter a valid email address">
                        </div>
                        <div class="form-group">
                            <label>Author Affiliation</label>
                            <input type="text" name="author_affiliation" class="form-control"
                                   maxlength="255"
                                   pattern=".{0,255}"
                                   title="Affiliation must not exceed 255 characters">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleAdd('paper')">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Update Paper Forms (hidden by default) -->
            @foreach($papers as $paper)
            <div id="edit-paper-form-{{ $paper->id }}" class="edit-form" style="display: none;">
                <form method="POST" action="{{ route('admin.update-paper', $paper->id) }}" enctype="multipart/form-data" class="admin-form">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ $paper->title }}" 
                                   required 
                                   minlength="5"
                                   maxlength="255"
                                   pattern=".{5,255}"
                                   title="Title must be between 5 and 255 characters">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Abstract *</label>
                            <textarea name="abstract" class="form-control" rows="4" 
                                      required
                                      minlength="50"
                                      title="Abstract must be at least 50 characters long">{{ $paper->abstract }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Publication Year *</label>
                            <input type="number" name="publication_year" class="form-control" 
                                   value="{{ $paper->publication_year }}"
                                   min="1900" 
                                   max="{{ date('Y') + 1 }}" 
                                   required
                                   step="1"
                                   title="Publication year must be between 1900 and {{ date('Y') + 1 }}">
                        </div>
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category_id" class="form-control" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $paper->categories->contains($category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Uploaded By *</label>
                            <select name="uploaded_by" class="form-control" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $paper->uploaded_by == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $paper->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $paper->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $paper->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>PDF File (Leave blank to keep current file)</label>
                            <input type="file" name="pdf_file" class="form-control" 
                                   accept=".pdf,application/pdf" 
                                   title="Please upload a PDF file (max 10MB)">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleEdit('paper', {{ $paper->id }})">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            @endforeach

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
                                <td>{{ Str::limit($paper->title, 50) }}</td>
                                <td>{{ $paper->uploader->name ?? 'N/A' }}</td>
                                <td><span class="status-badge status-{{ $paper->status }}">{{ ucfirst($paper->status) }}</span></td>
                                <td>{{ $paper->categories->first()->name ?? 'N/A' }}</td>
                                <td>{{ $paper->publication_year }}</td>
                                <td>{{ $paper->created_at ? $paper->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="actions-cell">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="adminForms.toggleEdit('paper', {{ $paper->id }})">Update</button>
                                    <form method="POST" action="{{ route('admin.delete-paper', $paper->id) }}" onsubmit="return confirm('Are you sure you want to delete this paper?');">
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
                <button type="button" class="btn btn-primary" onclick="adminForms.toggleAdd('author')">Add Author</button>
            </div>

            <!-- Add Author Form -->
            <div id="add-author-form" class="add-form" style="display: none;">
                <form method="POST" action="{{ route('admin.store-author') }}" class="admin-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Paper *</label>
                            <select name="paper_id" class="form-control" required>
                                <option value="">Select Paper</option>
                                @foreach($papers as $paper)
                                    <option value="{{ $paper->id }}">{{ $paper->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Author Name *</label>
                            <input type="text" name="author_name" class="form-control" 
                                   required 
                                   minlength="2"
                                   maxlength="150"
                                   pattern="[A-Za-z\s]{2,}"
                                   title="Author name must be at least 2 characters and contain only letters and spaces">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="author_email" class="form-control"
                                   maxlength="150"
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   title="Please enter a valid email address">
                        </div>
                        <div class="form-group">
                            <label>Affiliation</label>
                            <input type="text" name="affiliation" class="form-control"
                                   maxlength="255"
                                   pattern=".{0,255}"
                                   title="Affiliation must not exceed 255 characters">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleAdd('author')">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Update Author Forms (hidden by default) -->
            @foreach($authors as $author)
            <div id="edit-author-form-{{ $author->id }}" class="edit-form" style="display: none;">
                <form method="POST" action="{{ route('admin.update-author', $author->id) }}" class="admin-form">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label>Paper *</label>
                            <select name="paper_id" class="form-control" required>
                                @foreach($papers as $paper)
                                    <option value="{{ $paper->id }}" {{ $author->paper_id == $paper->id ? 'selected' : '' }}>{{ $paper->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Author Name *</label>
                            <input type="text" name="author_name" class="form-control" value="{{ $author->author_name }}" 
                                   required 
                                   minlength="2"
                                   maxlength="150"
                                   pattern="[A-Za-z\s]{2,}"
                                   title="Author name must be at least 2 characters and contain only letters and spaces">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="author_email" class="form-control" value="{{ $author->author_email }}"
                                   maxlength="150"
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   title="Please enter a valid email address">
                        </div>
                        <div class="form-group">
                            <label>Affiliation</label>
                            <input type="text" name="affiliation" class="form-control" value="{{ $author->affiliation }}"
                                   maxlength="255"
                                   pattern=".{0,255}"
                                   title="Affiliation must not exceed 255 characters">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleEdit('author', {{ $author->id }})">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            @endforeach

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
                                    <button type="button" class="btn btn-primary btn-sm" onclick="adminForms.toggleEdit('author', {{ $author->id }})">Update</button>
                                    <form method="POST" action="{{ route('admin.delete-author', $author->id) }}" onsubmit="return confirm('Are you sure you want to delete this author?');">
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
                <button type="button" class="btn btn-primary" onclick="adminForms.toggleAdd('category')">Add Category</button>
            </div>

            <!-- Add Category Form -->
            <div id="add-category-form" class="add-form" style="display: none;">
                <form method="POST" action="{{ route('admin.store-category') }}" class="admin-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category Name *</label>
                            <input type="text" name="name" class="form-control" 
                                   required 
                                   minlength="2"
                                   maxlength="100"
                                   pattern="[A-Za-z0-9\s]{2,}"
                                   title="Category name must be at least 2 characters and contain only letters, numbers, and spaces">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleAdd('category')">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Update Category Forms (hidden by default) -->
            @foreach($categories as $category)
            <div id="edit-category-form-{{ $category->id }}" class="edit-form" style="display: none;">
                <form method="POST" action="{{ route('admin.update-category', $category->id) }}" class="admin-form">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" 
                                   required 
                                   minlength="2"
                                   maxlength="100"
                                   pattern="[A-Za-z0-9\s]{2,}"
                                   title="Category name must be at least 2 characters and contain only letters, numbers, and spaces">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-outline" onclick="adminForms.toggleEdit('category', {{ $category->id }})">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            @endforeach

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
                                    <button type="button" class="btn btn-primary btn-sm" onclick="adminForms.toggleEdit('category', {{ $category->id }})">Update</button>
                                    <form method="POST" action="{{ route('admin.delete-category', $category->id) }}" onsubmit="return confirm('Are you sure you want to delete this category?');">
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

<style>
.admin-dashboard {
    padding: 40px 0;
    min-height: calc(100vh - 200px);
    background-color: #f8f9fa;
    scroll-behavior: smooth;
}

.admin-header {
    text-align: center;
    margin-bottom: 40px;
}

.admin-header h1 {
    color: #2d5016;
    font-size: 36px;
    margin-bottom: 10px;
}

.admin-section {
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    scroll-margin-top: 100px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    color: #2d5016;
    font-size: 24px;
}

.table-responsive {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.admin-table th,
.admin-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.admin-table th {
    background-color: #2d5016;
    color: #ffffff;
    font-weight: 600;
}

.admin-table tr:hover {
    background-color: #f8f9fa;
}

.admin-table td:last-child {
    white-space: nowrap;
}

.admin-table .actions-cell {
    display: flex;
    gap: 8px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.admin-table .actions-cell form {
    margin: 0;
    display: inline-block;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-approved {
    background-color: #d4edda;
    color: #155724;
}

.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.add-form, .edit-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #2d5016;
}

.edit-form {
    background: #fff9e6;
    border-color: #ffc107;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: #2d5016;
    margin-bottom: 5px;
    font-size: 14px;
}

.form-control {
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #2d5016;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
    margin: 0 2px;
    display: inline-block;
}

.btn-primary {
    background-color: #2d5016;
    color: #ffffff;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #1f350f;
}

.btn-danger {
    background-color: #dc3545;
    color: #ffffff;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-danger:hover {
    background-color: #c82333;
}

.btn-outline {
    background-color: transparent;
    color: #2d5016;
    border: 2px solid #2d5016;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-outline:hover {
    background-color: #2d5016;
    color: #ffffff;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
</style>
@endsection

