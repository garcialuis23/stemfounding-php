@extends('layouts.app')
@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
<div class="container mt-5" style="background-color: #fbf5e9;">
    <!-- Card Section -->
    <div class="row">
        <div class="col-md-6">
            <div id="projectCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset($project->url_image) }}" class="d-block w-100" alt="{{ $project->title }}"
                            style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <iframe class="d-block w-100" src="{{ $project->url_video }}" frameborder="0" allowfullscreen
                            style="height: 400px;"></iframe>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#projectCarousel" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#projectCarousel" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-lg" style="border-radius: 15px; background-color: #fdf7ee;">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->title }}</h5>
                    <p class="card-text">{{ $project->description }}</p>
                    <p><strong>Minimum Investment:</strong> {{ $project->min_investment }}</p>
                    <p><strong>Maximum Investment:</strong> {{ $project->max_investment }}</p>
                    <p><strong>Limit Date:</strong> {{ $project->limit_date }}</p>
                    <p>
                        <strong>Status:</strong>
                        @if($project->status == 'active')
                            ✅
                        @elseif($project->status == 'pending')
                            ⚠️
                        @elseif($project->status == 'inactive')
                            💤
                        @endif
                    </p>
                    <p><strong>User Name:</strong> <a
                            href="{{ route('home.show', $project->user->id) }}">{{ $project->user->name }}</a></p>
                    <p><strong>Current Investment:</strong> {{ $project->current_investment }}</p>

                    <!-- Progress Bar -->
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ ($project->current_investment / $project->max_investment) * 100 }}%;"
                            aria-valuenow="{{ $project->current_investment }}" aria-valuemin="0"
                            aria-valuemax="{{ $project->max_investment }}">
                            {{ number_format(($project->current_investment / $project->max_investment) * 100, 2) }}%
                        </div>
                    </div>

                    @if(Auth::user()->role == 'admin' || Auth::user()->id == $project->user->id)
                        <div class="d-flex justify-content-between">

                            @if(Auth::user()->role == 'admin' && $project->status == 'active' || Auth::user()->id == $project->user->id && $project->status == 'active')
                                <form method="POST" action="{{ route('projects.updateStatus', $project->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="status" value="inactive" class="btn btn-secondary">Inactive
                                        Project</button>
                                </form>
                            @endif

                            @if(Auth::user()->id == $project->user->id && Auth::user()->role == 'emprendedor' && $project->status == 'active')
                                <!-- Button trigger modal for adding comments -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addCommentModal">
                                    Add Comment
                                </button>
                            @endif

                            @if(Auth::user()->id == $project->user->id && Auth::user()->role == 'emprendedor')
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#editProjectModal">
                                    Edit Project
                                </button>
                            @endif

                        </div>
                    @endif
                    @if(Auth::user()->role == 'investor' && $project->status == 'active')
                        <form method="POST" action="{{ route('projects.invest', $project->id) }}">
                            @csrf
                            <div class="mb-3">
                                <input type="number" class="form-control" id="investment_amount" name="investment_amount"
                                    min="10" step="0.01"
                                    max="{{ min(Auth::user()->balance, $project->max_investment - $project->current_investment) }}"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Invest</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('projects.update', $project->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ $project->title }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"
                                maxlength="255">{{ $project->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="url_image" class="form-label">Image URL</label>
                            <input type="text" class="form-control" id="url_image" name="url_image"
                                value="{{ $project->url_image }}">
                        </div>
                        <div class="mb-3">
                            <label for="url_video" class="form-label">Video URL</label>
                            <input type="text" class="form-control" id="url_video" name="url_video"
                                value="{{ $project->url_video }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Comment Modal -->
    <div class="modal fade" id="addCommentModal" tabindex="-1" aria-labelledby="addCommentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommentModalLabel">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('projects.addComment', $project->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="comment_image" class="form-label">Comment Image URL</label>
                            <input type="text" class="form-control" id="comment_image" name="comment_image">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    @if($project->investments->isNotEmpty())
        <!-- Table Section -->
        <div class="row mt-5">
            <div class="col-12">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Money</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->investments as $investment)
                            <tr>
                                <td>{{ $investment->user->name }}</td>
                                <td>{{ $investment->amount }}€</td>
                                <td>{{ $investment->created_at }}</td>
                                <td>
                                    @if($project->status == 'active' && Auth::user()->id == $investment->user_id && (new DateTime($investment->created_at))->modify('+24 hours') > new DateTime())
                                        <form method="POST" action="{{ route('projects.withdrawInvestment', $investment->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Withdraw</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif


    <!-- Comments Section -->
    <div class="row mt-5">
        <div class="col-12">
            @foreach($project->comments ?? [] as $comment)
                <div class="card mb-3 shadow-sm comment-card">
                    <div class="card-body">
                        <p class="comment-text">{{ $comment['comment'] }}</p>
                        @if($comment['comment_image'])
                            <img src="{{ $comment['comment_image'] }}" class="img-fluid rounded comment-image"
                                alt="Comment Image">
                        @endif
                        <p class="text-muted comment-meta">
                            <small>Created at: {{ $comment['created_at'] }}</small>
                            <small>Last edited: {{ $comment['updated_at'] }}</small>
                        </p>
                        @if(Auth::user()->id == $project->user->id)
                            <div class="comment-actions">
                                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal"
                                    data-bs-target="#editCommentModal{{ $loop->index }}">
                                    Edit
                                </button>
                            </div>
                        @endif

                        @if(Auth::user()->id == $project->user->id || Auth::user()->role == 'admin')
                            <form method="POST"
                                action="{{ route('projects.deleteComment', [$project->id, $loop->index]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Edit Comment Modal -->
                <div class="modal fade" id="editCommentModal{{ $loop->index }}" tabindex="-1"
                    aria-labelledby="editCommentModalLabel{{ $loop->index }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCommentModalLabel{{ $loop->index }}">Edit Comment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST"
                                action="{{ route('projects.updateComment', [$project->id, $loop->index]) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Comment</label>
                                        <textarea class="form-control" id="comment"
                                            name="comment">{{ $comment['comment'] }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment_image" class="form-label">Comment Image URL</label>
                                        <input type="text" class="form-control" id="comment_image" name="comment_image"
                                            value="{{ $comment['comment_image'] }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush