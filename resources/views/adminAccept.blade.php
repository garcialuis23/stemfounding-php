@extends('layouts.app')
@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
@if (Auth::guest() || Auth::user()->role !== 'admin')
    <script>window.location = "{{ route('login') }}";</script>
@else
    <div class="container mt-3">

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <h3>Pending Projects</h3>
        <div class="row">
            @forelse($projects as $project)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                            </h5>
                            <p class="card-text">{{ ($project->description) }}</p>
                            @if($project->status == 'pending')
                                <p><strong> Status:</strong>⚠️</p>
                            @endif
                            <form method="POST" action="{{ route('projects.updateStatus', $project->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="d-flex justify-content-between">
                                    <button type="submit" name="status" value="active" class="btn btn-primary">Active</button>
                                    <button type="submit" name="status" value="rejected"
                                        class="btn btn-secondary">Rejected</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <p class="card-text">No pending projects</p>
                        </div>
                    </div>
            @endforelse

            </div>
        </div>
@endif
    @endsection