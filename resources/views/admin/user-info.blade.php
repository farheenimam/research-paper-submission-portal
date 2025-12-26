@extends('layout')

@section('title', 'User Information - ' . $user->name)

@section('content')
<div class="user-info-page">
    <div class="container">
        <div class="page-header">
            <a href="{{ route('admin.dashboard') }}" class="back-link">← Back to Dashboard</a>
            <h1>User Information</h1>
        </div>

        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <!-- User Basic Information -->
        <div class="info-section">
            <h2>Basic Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Name:</label>
                    <span>{{ $user->name }}</span>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <label>Role:</label>
                    <span class="role-badge role-{{ strtolower($user->role->name ?? 'user') }}">{{ ucfirst($user->role->name ?? 'N/A') }}</span>
                </div>
                <div class="info-item">
                    <label>Affiliation:</label>
                    <span>{{ $user->affiliation ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Bio:</label>
                    <span>{{ $user->bio ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Member Since:</label>
                    <span>{{ $user->created_at ? $user->created_at->format('F d, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- User Activities -->
        <div class="info-section">
            <h2>Activities</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $uploadedPapers->count() }}</div>
                    <div class="stat-label">Papers Uploaded</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $authoredPapers->count() }}</div>
                    <div class="stat-label">Papers Authored</div>
                </div>
                @if($user->role_id == 3)
                <div class="stat-card">
                    <div class="stat-number">{{ $comments->count() }}</div>
                    <div class="stat-label">Reviews Given</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $approvedPapers->count() }}</div>
                    <div class="stat-label">Papers Approved</div>
                </div>
                @endif
                @if($user->role_id == 2 || $user->role_id == 1)
                <div class="stat-card">
                    <div class="stat-number">{{ count($feedbacks) }}</div>
                    <div class="stat-label">Feedbacks Received</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Uploaded Papers (Researcher) -->
        @if($uploadedPapers->count() > 0)
        <div class="info-section">
            <h2>Uploaded Papers</h2>
            <div class="table-responsive">
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Year</th>
                            <th>Uploaded</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uploadedPapers as $paper)
                            <tr>
                                <td><a href="{{ route('paper.view', $paper->id) }}" target="_blank">{{ Str::limit($paper->title, 60) }}</a></td>
                                <td><span class="status-badge status-{{ $paper->status }}">{{ ucfirst($paper->status) }}</span></td>
                                <td>{{ $paper->categories->first()->name ?? 'N/A' }}</td>
                                <td>{{ $paper->publication_year }}</td>
                                <td>{{ $paper->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Authored Papers -->
        @if($authoredPapers->count() > 0)
        <div class="info-section">
            <h2>Authored Papers</h2>
            <div class="table-responsive">
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author Name</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authoredPapers as $authorPaper)
                            <tr>
                                <td><a href="{{ route('paper.view', $authorPaper->paper->id) }}" target="_blank">{{ Str::limit($authorPaper->paper->title, 60) }}</a></td>
                                <td>{{ $authorPaper->author_name }}</td>
                                <td><span class="status-badge status-{{ $authorPaper->paper->status }}">{{ ucfirst($authorPaper->paper->status) }}</span></td>
                                <td>{{ $authorPaper->paper->categories->first()->name ?? 'N/A' }}</td>
                                <td>{{ $authorPaper->paper->publication_year }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Reviewer Comments -->
        @if($user->role_id == 3 && $comments->count() > 0)
        <div class="info-section">
            <h2>Reviews & Comments</h2>
            <div class="comments-list">
                @foreach($comments as $comment)
                    <div class="comment-card">
                        <div class="comment-header">
                            <div class="comment-paper">
                                <strong>Paper:</strong> 
                                <a href="{{ route('paper.view', $comment->paper->id) }}" target="_blank">{{ Str::limit($comment->paper->title, 80) }}</a>
                            </div>
                            <div class="comment-date">{{ $comment->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="comment-body">
                            <p>{{ $comment->comment }}</p>
                        </div>
                        <div class="comment-footer">
                            <span class="paper-status">Status: <span class="status-badge status-{{ $comment->paper->status }}">{{ ucfirst($comment->paper->status) }}</span></span>
                            <span class="paper-category">Category: {{ $comment->paper->categories->first()->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Approved Papers (Reviewer) -->
        @if($user->role_id == 3 && $approvedPapers->count() > 0)
        <div class="info-section">
            <h2>Approved Papers</h2>
            <div class="table-responsive">
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Uploaded By</th>
                            <th>Approved Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedPapers as $paper)
                            <tr>
                                <td><a href="{{ route('paper.view', $paper->id) }}" target="_blank">{{ Str::limit($paper->title, 60) }}</a></td>
                                <td><span class="status-badge status-{{ $paper->status }}">{{ ucfirst($paper->status) }}</span></td>
                                <td>{{ $paper->categories->first()->name ?? 'N/A' }}</td>
                                <td>{{ $paper->uploader->name ?? 'N/A' }}</td>
                                <td>{{ $paper->updated_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Feedbacks Received (Researcher) -->
        @if(($user->role_id == 2 || $user->role_id == 1) && count($feedbacks) > 0)
        <div class="info-section">
            <h2>Feedbacks Received</h2>
            <div class="feedbacks-list">
                @foreach($feedbacks as $feedback)
                    <div class="feedback-card">
                        <div class="feedback-header">
                            <div class="feedback-paper">
                                <strong>Paper:</strong> 
                                <a href="{{ route('paper.view', $feedback['paper']->id) }}" target="_blank">{{ Str::limit($feedback['paper']->title, 80) }}</a>
                            </div>
                            <div class="feedback-reviewer">
                                <strong>Reviewer:</strong> {{ $feedback['reviewer']->name }}
                            </div>
                            <div class="feedback-date">{{ $feedback['comment']->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="feedback-body">
                            <p>{{ $feedback['comment']->comment }}</p>
                        </div>
                        <div class="feedback-footer">
                            <span class="paper-status">Status: <span class="status-badge status-{{ $feedback['paper']->status }}">{{ ucfirst($feedback['paper']->status) }}</span></span>
                            <span class="paper-category">Category: {{ $feedback['paper']->categories->first()->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Empty States -->
        @if($uploadedPapers->count() == 0 && $authoredPapers->count() == 0 && $comments->count() == 0 && count($feedbacks) == 0)
        <div class="info-section">
            <div class="empty-state">
                <p>No activities found for this user.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.user-info-page {
    padding: 40px 0;
    min-height: calc(100vh - 200px);
    background-color: #f8f9fa;
}

.page-header {
    margin-bottom: 30px;
}

.back-link {
    display: inline-block;
    color: #2d5016;
    text-decoration: none;
    margin-bottom: 15px;
    font-weight: 600;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #1f350f;
    text-decoration: underline;
}

.page-header h1 {
    color: #2d5016;
    font-size: 32px;
    margin: 0;
}

.info-section {
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.info-section h2 {
    color: #2d5016;
    font-size: 24px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e0e0e0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-item label {
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
    font-size: 14px;
}

.info-item span {
    color: #333;
    font-size: 16px;
}

.role-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.role-admin {
    background-color: #dc3545;
    color: #ffffff;
}

.role-reviewer {
    background-color: #ffc107;
    color: #000000;
}

.role-researcher {
    background-color: #17a2b8;
    color: #ffffff;
}

.role-reader {
    background-color: #6c757d;
    color: #ffffff;
}

.role-user {
    background-color: #28a745;
    color: #ffffff;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: linear-gradient(135deg, #2d5016 0%, #1f350f 100%);
    color: #ffffff;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 10px;
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
}

.table-responsive {
    overflow-x: auto;
}

.info-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.info-table th,
.info-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.info-table th {
    background-color: #2d5016;
    color: #ffffff;
    font-weight: 600;
}

.info-table tr:hover {
    background-color: #f8f9fa;
}

.info-table a {
    color: #2d5016;
    text-decoration: none;
    font-weight: 600;
}

.info-table a:hover {
    text-decoration: underline;
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

.comments-list, .feedbacks-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.comment-card, .feedback-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #2d5016;
}

.comment-header, .feedback-header {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
}

.comment-paper, .feedback-paper {
    flex: 1;
    min-width: 200px;
}

.comment-paper a, .feedback-paper a {
    color: #2d5016;
    text-decoration: none;
    font-weight: 600;
}

.comment-paper a:hover, .feedback-paper a:hover {
    text-decoration: underline;
}

.comment-date, .feedback-date {
    color: #666;
    font-size: 14px;
}

.feedback-reviewer {
    color: #333;
    font-weight: 600;
}

.comment-body, .feedback-body {
    margin-bottom: 15px;
}

.comment-body p, .feedback-body p {
    color: #333;
    line-height: 1.6;
    margin: 0;
}

.comment-footer, .feedback-footer {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 14px;
    color: #666;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
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

