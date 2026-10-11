@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- back to index --}}
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary mb-3">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                </a>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-user me-2"></i>My Profile</span>


                        {{-- delete for dropdown menu --}}
                        <div class="dropdown">
                            <button type="button" class="btn btn-link text-secondary p-0 border-0 shadow-none"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteAccountModal">
                                        <i class="fa-solid fa-trash-can me-2"></i>Delete Account
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @include('users.modal.delete', ['user' => $user])


                    <div class="card-body py-4">
                        <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                            {{-- @if ($errors->any())
                                <div class="alert aleret-danger mb-4">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif --}}
                            @csrf
                            @method('PATCH')
                            {{-- avatar icon --}}
                            <div class="row mb-4">
                                <div class="col-12 text-center">
                                    @if ($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                            class="rounded-circle img-thumbnail shadow-sm"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <i class="fa-solid fa-circle-user fa-6x text-secondary"></i>
                                    @endif
                                </div>
                            </div>

                            {{-- Current information file Upload --}}
                            <div class="row mb-3 align-items-center">
                                <label for="avatar"
                                    class="col-md-4 col-form-label text-md-end fw-semibold">{{ __('Profile Image') }}</label>
                                <div class="col-md-6">
                                    <input type="file" id="avatar" class="form-control" name="avatar">
                                    @error('avatar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end fw-semibold">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input type="text" id="name" name="name" class="d-flex align-items-center form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email address --}}
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end fw-semibold">{{ __('Email Address') }}</label>
                                <div class="col-md-6">
                                    <input type="email" id="email" name="email" class="form-control d-flex align-items-center"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- User Type Readonly --}}
                            <div class="row mb-3">
                                <label
                                    class="col-md-4 col-form-label text-md-end fw-semibold">{{ __('User Type') }}</label>
                                <div class="col-md-6 d-flex align-items-center pt-1">
                                    <div class="form-check me-4">
                                        <input type="radio" class="form-check-input"
                                            {{ $user->role === 'seeker' ? 'checked' : '' }} disabled>
                                        <label class="form-check-label text-muted">Seeker</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input"
                                            {{ $user->role === 'owner' ? 'checked' : '' }} disabled>
                                        <label class="form-check-label text-muted">Owner</label>
                                    </div>
                                    <small class="text-muted ms-2">(Cannot be changed)</small>
                                </div>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="row mb-0 mt-4">
                                <div class="col-md-6 offset-md-4 d-flex justify-content-between">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-warning">Save Changes</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
