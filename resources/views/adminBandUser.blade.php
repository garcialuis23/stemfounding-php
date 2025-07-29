@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
@if (Auth::guest() || Auth::user()->role !== 'admin')
    <script>window.location = "{{ route('login') }}";</script>
@else
    <div class="container mt-4 p-4 rounded bg-light shadow">
        {{-- Display error messages --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Display success messages --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h3 class="text-center mb-4 text-dark">User Management</h3>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('adminBandUser') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <select name="status" class="form-control bg-light border-accent">
                        <option value="">All Users</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        <option value="not_banned" {{ request('status') == 'not_banned' ? 'selected' : '' }}>Not Banned</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-light border-accent">
                        <thead class="bg-dark text-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registration Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-accent">
                                    <td>{{ $user->id }}</td>
                                    <td><a href="{{ route('home.show', $user->id) }}">{{ $user->name }}</a></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if ($user->is_banned)
                                            <span class="badge bg-secondary">Banned</span>
                                        @else
                                            <span class="badge bg-primary">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('user.ban', $user->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm {{ $user->is_banned ? 'btn-primary' : 'btn-secondary' }}">
                                                {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-dark">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection