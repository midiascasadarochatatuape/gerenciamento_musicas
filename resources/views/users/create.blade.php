@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Novo usuário</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="mb-3">
                    <label for="type_user" class="form-label">Tipo</label>
                    <select class="form-select @error('type_user') is-invalid @enderror"
                            id="type_user" name="type_user" required>
                        <option value="">Select a role</option>
                        <option value="admin" {{ old('type_user') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="tecnico" {{ old('type_user') == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="musico" {{ old('type_user') == 'musico' ? 'selected' : '' }}>Músico</option>
                    </select>
                    @error('type_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('user.index') }}" class="btn btn-danger me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Savar usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
