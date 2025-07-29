@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <!-- Filtro y botón en la misma línea -->
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <form method="GET" action="{{ route('projects.unlogged') }}" id="filterForm"
                class="d-flex align-items-center">
                <label for="status" class="fw-bold me-3 mb-0">Filter by Status:</label>
                <select id="status" name="status" class="form-control w-auto"
                    onchange="document.getElementById('filterForm').submit();">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>
            @auth
                @if (Auth::user()->role == 'emprendedor')
                    <a href="{{ route('newProject') }}" class="btn btn-secondary shadow-lg">{{ __('New Project') }}</a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Lista de proyectos -->
    <div class="row">
        @foreach($projects as $project)
            <div class="col-md-4 mb-4">
                <div class="card mb-4 shadow-lg h-100 rounded-lg border border-3 {{ $project->status == 'inactive' ? 'border-danger' : 'border-primary' }}"
                    style="transition: transform 0.3s, box-shadow 0.3s; background-color: #fff;">
                    <div class="card-img-top-container position-relative">
                        <img src="{{ $project->status == 'inactive' ? 'https://www.shutterstock.com/image-vector/inactive-grunge-rubber-stamp-on-260nw-607855559.jpg' : $project->url_image }}"
                            class="card-img-top rounded-top border-bottom border-secondary" alt="{{ $project->title }}"
                            style="height: 200px; object-fit: cover;">
                        @if ($project->status == 'inactive')
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white fs-4"
                                style="background-color: rgba(0, 0, 0, 0.5);">
                            </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary fw-bold">{{ $project->title }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($project->description, 100) }}</p>
                        <p class="mb-2"><strong>Minimum Investment:</strong> {{ $project->min_investment }}</p>
                        <p><strong>Cut-off time:</strong> {{ $project->limit_date }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @auth
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary shadow-sm">View
                                    Project</a>
                            @else
                                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#loginModal">
                                    View Project
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination shadow-lg rounded-lg">
                @if ($projects->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link text-muted">&laquo; Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link text-primary" href="{{ $projects->previousPageUrl() }}" aria-label="Previous">
                            &laquo; Previous
                        </a>
                    </li>
                @endif

                @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $projects->currentPage() ? 'active' : '' }}">
                        <a class="page-link {{ $page == $projects->currentPage() ? 'bg-primary text-white' : 'text-primary' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($projects->hasMorePages())
                    <li class="page-item">
                        <a class="page-link text-primary" href="{{ $projects->nextPageUrl() }}" aria-label="Next">
                            Next &raquo;
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link text-muted">Next &raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg rounded-lg border border-secondary">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="loginModalLabel">Not Logged In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You are not logged in. Please log in to view the project details.
                </div>
                <div class="modal-footer">
                    <a href="{{ route('login') }}" class="btn btn-primary shadow">Login</a>
                    <button type="button" class="btn btn-secondary shadow" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection