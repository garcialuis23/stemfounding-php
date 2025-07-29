@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh; background-color: #fbf5e9;">
        <div class="col-md-5">
            <div class="card shadow-lg" style="border-radius: 15px; background-color: #fdf7ee;">
                <div class="card-body">
                    <h3 class="text-center mb-4" style="color: #4e5d6c;">{{ __('Register') }}</h3>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <!-- Profile Image URL -->
                        <div class="mb-3">
                            <label for="url_img" class="form-label">{{ __('Profile Image URL') }}</label>
                            <input id="url_img" type="text" class="form-control @error('url_img') is-invalid @enderror" 
                                   name="url_img" value="{{ old('url_img') }}" required>
                            @error('url_img')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="role" class="form-label">{{ __('Role') }}</label>
                            <select id="role" class="form-select @error('role') is-invalid @enderror" 
                                    name="role" required>
                                <option value="emprendedor">{{ __('Emprendedor') }}</option>
                                <option value="investor">{{ __('Inversor') }}</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                {{ __('Register') }}
                            </button>
                            @if (Route::has('login'))
                                <a class="btn btn-link text-center w-100" href="{{ route('login') }}">
                                    {{ __('Already have an account? Login') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
