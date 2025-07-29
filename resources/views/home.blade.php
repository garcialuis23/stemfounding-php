@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-5">
        <!-- Imagen del perfil -->
        <div class="col-md-3 text-center">
            <img src="{{ $user->url_img }}" alt="{{ $user->name }}" class="img-fluid rounded-circle shadow"
                style="max-width: 200px; border: 5px solid #E6CBA8;">
        </div>
        <!-- Información del usuario -->
        <div class="col-md-9">
            <div class="bg-light p-4 rounded shadow-sm border">
                <h4 class="fw-bold text-primary">{{ $user->name }}</h4>
                <p class="text-muted mb-2"><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
                <p class="text-muted mb-2"><strong>{{ __('Role:') }}</strong> {{ $user->role }}</p>
                @if (Auth::user()->role == 'admin' && Auth::user()->id !== $user->id)
                    <form method="POST" action="{{ route('user.changeRole', $user->id) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </form>
                @endif
                </p>
                @if (Auth::id() == $user->id)
                    <p class="text-muted mb-2"><strong>{{ __('Change password:') }}</strong> <a
                            href="{{ route('password.change') }}" class="text-primary">{{ __('here') }}</a></p>
                @endif
                <p class="text-muted mb-0"><strong>{{ __('Balance:') }}</strong> {{ $user->balance }} €</p>


                @if (Auth::id() == $user->id && (Auth::user()->role == 'investor' || Auth::user()->role == 'emprendedor'))
                    <!-- Button trigger modal -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            {{ __('Edit Profile') }}
                        </button>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProfileModalLabel">{{ __('Edit Profile') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('user.update', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('Name:') }}</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $user->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('Email:') }}</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $user->email }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-end align-items-center mt-3">
                @if (Auth::user()->role == 'admin' && Auth::user()->id !== $user->id)
                    <form method="POST" action="{{ route('user.ban', $user->id) }}" class="me-3">
                        @csrf
                        @method('PUT')
                        @if ($user->is_banned)
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Unban') }}</button>
                        @else
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Ban') }}</button>
                        @endif
                    </form>
                @endif

                @if (Auth::user()->role == 'investor' && Auth::user()->id == $user->id)
                    <!-- Botón para abrir el modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#foundsModal">
                        {{ __('Founds') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-primary shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-primary shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <!-- Proyectos del usuario -->
    @if ($user->role == 'emprendedor')
        @foreach ([['status' => 'active', 'title' => 'Active Projects', 'icon' => 'check-circle-fill', 'color' => 'primary'], ['status' => 'pending', 'title' => 'Pending Projects', 'icon' => 'hourglass-split', 'color' => 'warning'], ['status' => 'inactive', 'title' => 'Inactive Projects', 'icon' => 'moon', 'color' => 'black']] as $projectGroup)
            <h3 class="mb-4 text-{{ $projectGroup['color'] }}">
                <i class="bi bi-{{ $projectGroup['icon'] }}"></i> {{ $projectGroup['title'] }}
            </h3>
            <div class="row">
                @foreach($user->projects->where('status', $projectGroup['status']) ?? [] as $project)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 border border-{{ $projectGroup['color'] }}">
                            <div class="card-body">
                                <h5 class="card-title text-{{ $projectGroup['color'] }}">{{ $project->title }}</h5>
                                <p class="card-text">{{ $project->description }}</p>
                                <a href="{{ route('projects.show', $project->id) }}"
                                    class="btn btn-outline-{{ $projectGroup['color'] }} btn-sm">{{ __('About') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        @if ($user->projects->isEmpty())
            <p>{{ __('You have not created any projects yet.') }}</p>
        @endif
    @elseif ($user->role == 'investor')
        <h3 class="mb-4 text-primary">
            <i class="bi bi-cash-stack"></i> {{ __('My Investments') }}
        </h3>
        <div class="row">
            @foreach($user->investments->groupBy('project_id') as $projectId => $investments)
                <div class="col-12 mb-4">
                    <h4 class="text-marroncito">{{ $investments->first()->project->title }}</h4>
                    <div class="row">
                        @foreach($investments as $investment)
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100 border border-primary">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <a
                                                href="{{ route('projects.show', $investment->project->id) }}">{{ $investment->project->title }}</a>
                                        </h5>
                                        <p class="card-text"><strong>{{ __('Invested Amount:') }}</strong> {{ $investment->amount }}
                                            €</p>
                                        <p class="card-text"><strong>{{ __('Investment Date:') }}</strong>
                                            {{ $investment->created_at }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        @if ($user->investments->isEmpty())
            <p>{{ __('You have not made any investments yet.') }}</p>
        @endif
    @endif
</div>

<!-- Modal para gestionar fondos -->
<div class="modal fade" id="foundsModal" tabindex="-1" aria-labelledby="foundsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="foundsModalLabel">{{ __('Manage Funds') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <!-- Información del usuario -->
                <div class="mb-4 text-center">
                    <p><strong>{{ __('Name:') }}</strong> {{ $user->name }}</p>

                    <!-- Formulario para actualizar IBAN -->
                    <form method="POST" action="{{ route('user.updateIBAN', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="IBAN" class="form-label fw-bold">{{ __('IBAN:') }}</label>
                            <p class="text-muted">
                                {{ __('Permitted account numbers:') }} Andorra, Islas Vírgenes Británicas, Moldavia,
                                Pakistán, Rumania, Arabia Saudita, Suecia, República Eslovaca, España, Chequia, Túnez
                            </p>
                            <input type="text" class="form-control @error('IBAN') is-invalid @enderror" id="IBAN"
                                name="IBAN" value="{{ $user->IBAN }}" required>
                            @error('IBAN')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __('Invalid IBAN. Please enter a valid IBAN.') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">{{ __('Update IBAN') }}</button>
                    </form>
                </div>

                <!-- Saldo actual -->
                <div class="text-center mb-4">
                    <p class="fw-bold">{{ __('Balance:') }} <span class="text-primary">{{ $user->balance }} €</span></p>
                </div>

                <!-- Formularios para retirar y depositar dinero -->
                <div class="row g-3">
                    <!-- Retirar dinero -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-center text-primary">{{ __('Withdraw Funds') }}</h5>
                                <form method="POST" action="{{ route('withdraw') }}">
                                    @csrf
                                    @if (empty($user->IBAN))
                                        <div class="alert alert-warning" role="alert">
                                            {{ __('You must have an IBAN to withdraw money.') }}
                                        </div>
                                    @endif
                                    <input type="number" name="amount" class="form-control mb-3" placeholder="€"
                                        step="0.01" required {{ empty($user->IBAN) ? 'disabled' : '' }}>
                                    <button type="submit" class="btn btn-primary w-100" {{ empty($user->IBAN) ? 'disabled' : '' }}>{{ __('Withdraw') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Depositar dinero -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-center text-primary">{{ __('Deposit Funds') }}</h5>
                                <form method="POST" action="{{ route('deposit') }}">
                                    @csrf
                                    @if (empty($user->IBAN))
                                        <div class="alert alert-warning" role="alert">
                                            {{ __('You must have an IBAN to deposit money.') }}
                                        </div>
                                    @endif
                                    <input type="number" name="amount" class="form-control mb-3" placeholder="€"
                                        step="0.01" required {{ empty($user->IBAN) ? 'disabled' : '' }}>
                                    <button type="submit" class="btn btn-primary w-100" {{ empty($user->IBAN) ? 'disabled' : '' }}>{{ __('Deposit') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection