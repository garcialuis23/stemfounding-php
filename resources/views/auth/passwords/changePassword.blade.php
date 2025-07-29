@extends('layouts.app')
@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!-- Super importante para no dejar que meta el usuario sin log -->
@section('content')
@if (Auth::guest())
    <script>window.location = "{{ route('login') }}";</script>
@else

    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh; background-color: #fbf5e9;">
            <div class="col-md-6">
                <div class="card shadow-lg" style="border-radius: 15px; background-color: #fdf7ee;">
                    <div class="card-body">
                        <h3 class="text-center mb-4" style="color: #4e5d6c;">{{ __('Change your password?') }}</h3>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <!-- Última contraseña -->
                            <div class="mb-3">
                                <label for="current_password"
                                    class="form-label">{{ __('Enter your last password') }}</label>
                                <input id="current_password" type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    name="current_password" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Nueva contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Enter a new password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Confirmar contraseña -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm') }}</label>
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>

                            <!-- Botón de reinicio -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Reset') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection