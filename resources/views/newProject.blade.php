@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh; background-color: #fbf5e9;">
            <div class="col-md-8">
                <div class="card shadow-lg" style="border-radius: 15px; background-color: #fdf7ee;">
                    <div class="card-body">
                        <h3 class="text-center mb-4" style="color: #4e5d6c;">{{ __('New Project') }}</h3>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Formulario añadido -->
                        <form method="POST" action="{{ route('projects.store') }}">
                            @csrf <!-- Token de seguridad para formularios en Laravel -->

                            <div class="mb-3">
                                <label for="title" class="form-label">Título:</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción:</label>
                                <textarea id="description" name="description" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="url_image" class="form-label">URL Imagen:</label>
                                <input type="text" id="url_image" name="url_image" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="url_video" class="form-label">URL Video:</label>
                                <input type="text" id="url_video" name="url_video" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="min_investment" class="form-label">Inversión Mínima:</label>
                                <input type="number" id="min_investment" name="min_investment" class="form-control" min="10"
                                    step="0.01" value="10" required>
                            </div>

                            <div class="mb-3">
                                <label for="max_investment" class="form-label">Inversión Máxima:</label>
                                <input type="number" id="max_investment" name="max_investment" class="form-control" min="15"
                                    step="0.01" value="100" max="9999999999.99" required>
                            </div>

                            <div class="mb-3">
                                <label for="limit_date" class="form-label">Fecha Límite:</label>
                                <input type="date" id="limit_date" name="limit_date" class="form-control" required
                                    min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection