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

        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <!-- Users Section -->
        <div id="users" class="admin-section">
            <div class="section-header">
                <h2>Users</h2>
                <button type="button" class="btn btn-primary" onclick="toggleAddForm('user')">Add User</button>
            </div>

            <!-- Add User Form -->
            <div id="add-user-form" class="add-form" style="display: none;">
                <form method="POST" action="{{ route('admin.store-user') }}" class="admin-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Role *</label>
                            <select name="role_id" class="form-control" required>
                                @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Affiliation</label>
                            <input type="text" name="affiliation" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="toggleAddForm('user')">Cancel</button>
                        </div>
                    </div>
                </form>
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
                                <td>{{ $user->affiliation ?? 'N/A' }}</td>
                                <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.delete-user', $user->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                <button type="button" class="btn btn-primary" onclick="toggleAddForm('paper')">Add Paper</button>
            </div>

            <!-- Add Paper Form -->
            <div id="add-paper-form" class="add-form" style="display: none;">
                <form method="POST" action="{{ route('admin.store-paper') }}" enctype="multipart/form-data" class="admin-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Abstract *</label>
                            <textarea name="abstract" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Publication Year *</label>
                            <input type="number" name="publication_year" class="form-control" 
                                   min="1900" max="{{ date('Y') + 1 }}" 
                                   value="{{ date('Y') }}" required>
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
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>PDF File *</label>
                            <input type="file" name="pdf_file" class="form-control" accept=".pdf" required>
                        </div>
                        <div class="form-group">
                            <label>Author Name *</label>
                            <input type="text" name="author_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Author Email</label>
                            <input type="email" name="author_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Author Affiliation</label>
                            <input type="text" name="author_affiliation" class="form-control">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="toggleAddForm('paper')">Cancel</button>
                        </div>
                    </div>
                </form>
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
                                <td>{{ Str::limit($paper->title, 50) }}</td>
                                <td>{{ $paper->uploader->name ?? 'N/A' }}</td>
                                <td><span class="status-badge status-{{ $paper->status }}">{{ ucfirst($paper->status) }}</span></td>
                                <td>{{ $paper->categories->first()->name ?? 'N/A' }}</td>
                                <td>{{ $paper->publication_year }}</td>
                                <td>{{ $paper->created_at ? $paper->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.delete-paper', $paper->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this paper?');">
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
                <button type="button" class="btn btn-primary" onclick="toggleAddForm('author')">Add Author</button>
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
                            <input type="text" name="author_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="author_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Affiliation</label>
                            <input type="text" name="affiliation" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="toggleAddForm('author')">Cancel</button>
                        </div>
                    </div>
                </form>
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
                                <td>
                                    <form method="POST" action="{{ route('admin.delete-author', $author->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this author?');">
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
                <button type="button" class="btn btn-primary" onclick="toggleAddForm('category')">Add Category</button>
            </div>

            <!-- Add Category Form -->
            <div id="add-category-form" class="add-form" style="display: none;">
                <form method="POST" action="{{ route('admin.store-category') }}" class="admin-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-outline" onclick="toggleAddForm('category')">Cancel</button>
                        </div>
                    </div>
                </form>
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
                                <td>
                                    <form method="POST" action="{{ route('admin.delete-category', $category->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
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

<script>
function toggleAddForm(type) {
    const form = document.getElementById('add-' + type + '-form');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>

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

.add-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
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

