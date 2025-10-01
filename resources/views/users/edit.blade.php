@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar dados de {{ old('name', $user->name) }}</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('user.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nome *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">E-mail *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="d-flex align-items-center mb-2">
                                <button type="button" id="togglePasswordBtn" class="btn btn-outline-primary px-4">
                                    <i class="fas fa-key me-1"></i>
                                    <span id="togglePasswordText">Alterar Senha</span>
                                </button>
                            </div>
                            <div id="passwordContainer" style="display: none;">
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" placeholder="Digite a nova senha" autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordVisibility">
                                        <i class="fas fa-eye" id="passwordIcon"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" type="button" id="clearPassword" title="Limpar campo">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Digite uma nova senha para alterar a atual</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->type_user === 'admin')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="group_1">Grupo Principal</label>
                            <select class="form-select form-control @error('group_1') is-invalid @enderror" id="group_1" name="group_1">
                                <option value="">Selecione...</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('group_1', $user->group_1) == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="group_2">Grupo Secundário</label>
                            <select class="form-select form-control @error('group_2') is-invalid @enderror" id="group_2" name="group_2">
                                <option value="">Selecione...</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('group_2', $user->group_2) == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instrument_1">Instrumento Principal</label>
                            <select class="form-select form-control @error('instrument_1') is-invalid @enderror" id="instrument_1" name="instrument_1">
                                <option value="" {{ old('instrument_1', $user->instrument_1) === '' ? 'selected' : '' }}>Selecione...</option>
                                <option value="Voz" {{ old('instrument_1', $user->instrument_1) === 'Voz' ? 'selected' : '' }}>Voz</option>
                                <option value="Guitarra" {{ old('instrument_1', $user->instrument_1) === 'Guitarra' ? 'selected' : '' }}>Guitarra</option>
                                <option value="Teclado" {{ old('instrument_1', $user->instrument_1) === 'Teclado' ? 'selected' : '' }}>Teclado</option>
                                <option value="Violão" {{ old('instrument_1', $user->instrument_1) === 'Violão' ? 'selected' : '' }}>Violão</option>
                                <option value="Bateria" {{ old('instrument_1', $user->instrument_1) === 'Bateria' ? 'selected' : '' }}>Bateria</option>
                                <option value="Baixo" {{ old('instrument_1', $user->instrument_1) === 'Baixo' ? 'selected' : '' }}>Baixo</option>
                                <option value="Sopro" {{ old('instrument_1', $user->instrument_1) === 'Sopro' ? 'selected' : '' }}>Sopro</option>
                                <option value="Cordas" {{ old('instrument_1', $user->instrument_1) === 'Cordas' ? 'selected' : '' }}>Cordas</option>
                            </select>
                            @error('instrument_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instrument_2">Instrumento Secundário <small>(se houver)</small></label>
                            <select class="form-select form-control @error('instrument_2') is-invalid @enderror" id="instrument_2" name="instrument_2">
                                <option value="" {{ old('instrument_2', $user->instrument_2) === '' ? 'selected' : '' }}>Selecione...</option>
                                <option value="Voz" {{ old('instrument_2', $user->instrument_2) === 'Voz' ? 'selected' : '' }}>Voz</option>
                                <option value="Guitarra" {{ old('instrument_2', $user->instrument_2) === 'Guitarra' ? 'selected' : '' }}>Guitarra</option>
                                <option value="Teclado" {{ old('instrument_2', $user->instrument_2) === 'Teclado' ? 'selected' : '' }}>Teclado</option>
                                <option value="Violão" {{ old('instrument_2', $user->instrument_2) === 'Violão' ? 'selected' : '' }}>Violão</option>
                                <option value="Bateria" {{ old('instrument_2', $user->instrument_2) === 'Bateria' ? 'selected' : '' }}>Bateria</option>
                                <option value="Baixo" {{ old('instrument_2', $user->instrument_2) === 'Baixo' ? 'selected' : '' }}>Baixo</option>
                                <option value="Sopro" {{ old('instrument_2', $user->instrument_2) === 'Sopro' ? 'selected' : '' }}>Sopro</option>
                                <option value="Cordas" {{ old('instrument_2', $user->instrument_2) === 'Cordas' ? 'selected' : '' }}>Cordas</option>
                            </select>
                            @error('instrument_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                @if(auth()->user()->type_user === 'admin')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type_user">Tipo de Usuário</label>
                                <select class="form-select form-control @error('type_user') is-invalid @enderror" id="type_user" name="type_user">
                                    <option value="musico" {{ old('type_user', $user->type_user) === 'musico' ? 'selected' : '' }}>Músico</option>
                                    <option value="tecnico" {{ old('type_user', $user->type_user) === 'tecnico' ? 'selected' : '' }}>Técnico</option>
                                    <option value="admin" {{ old('type_user', $user->type_user) === 'admin' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                @error('type_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-center gap-3 mt-5">
                    <a href="{{ route('user.index') }}" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Controle do botão Alterar Senha
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const togglePasswordText = document.getElementById('togglePasswordText');
        const passwordContainer = document.getElementById('passwordContainer');
        const passwordInput = document.getElementById('password');
        const togglePasswordVisibility = document.getElementById('togglePasswordVisibility');
        const passwordIcon = document.getElementById('passwordIcon');
        const clearPassword = document.getElementById('clearPassword');

        // Toggle do campo de senha
        togglePasswordBtn.addEventListener('click', function() {
            if (passwordContainer.style.display === 'none') {
                // Mostrar campo de senha
                passwordContainer.style.display = 'block';
                togglePasswordText.textContent = 'Cancelar';
                togglePasswordBtn.classList.remove('btn-outline-primary');
                togglePasswordBtn.classList.add('btn-outline-danger');
                passwordInput.focus();
            } else {
                // Esconder campo de senha
                passwordContainer.style.display = 'none';
                togglePasswordText.textContent = 'Alterar Senha';
                togglePasswordBtn.classList.remove('btn-outline-danger');
                togglePasswordBtn.classList.add('btn-outline-primary');
                // Não limpar o campo automaticamente - deixar o usuário decidir
            }
        });

        // Toggle da visibilidade da senha
        togglePasswordVisibility.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        });

        // Botão para limpar senha
        clearPassword.addEventListener('click', function() {
            passwordInput.value = '';
            passwordInput.focus();
        });

        // Grupos duplicados
        const group1Select = document.getElementById('group_1');
        const group2Select = document.getElementById('group_2');

        if (group1Select && group2Select) {
            function disableDuplicateOptions() {
                const selected1 = group1Select.value;
                const selected2 = group2Select.value;

                [...group1Select.options].forEach(opt => opt.disabled = opt.value && opt.value === selected2);
                [...group2Select.options].forEach(opt => opt.disabled = opt.value && opt.value === selected1);
            }

            group1Select.addEventListener('change', disableDuplicateOptions);
            group2Select.addEventListener('change', disableDuplicateOptions);

            disableDuplicateOptions(); // Inicial
        }

        // Instrumentos duplicados
        const inst1Select = document.getElementById('instrument_1');
        const inst2Select = document.getElementById('instrument_2');

        function disableDuplicateInstOptions() {
            const selected1 = inst1Select.value;
            const selected2 = inst2Select.value;

            [...inst1Select.options].forEach(opt => opt.disabled = opt.value && opt.value === selected2);
            [...inst2Select.options].forEach(opt => opt.disabled = opt.value && opt.value === selected1);
        }

        inst1Select.addEventListener('change', disableDuplicateInstOptions);
        inst2Select.addEventListener('change', disableDuplicateInstOptions);

        disableDuplicateInstOptions(); // Inicial
    });
</script>

@endsection
