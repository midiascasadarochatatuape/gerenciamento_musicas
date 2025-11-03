@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Novo Grupo</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('group.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nome do Grupo</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if ($errors->has('users'))
                    <div class="alert alert-danger">
                        {{ $errors->first('users') }}
                    </div>
                @endif

                @if ($errors->has('error'))
                    <div class="alert alert-danger">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                {{-- <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                <div id="users-container" class="row d-flex flex-column">
                    <div class="mb-3">
                        <label class="form-label">Participantes do Grupo</label>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <select class="form-select user-select" name="users[]" required>
                                    <option value="">Selecione um usuário</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-user-btn">
                        + Adicionar outro usuário
                    </button>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('group.index') }}" class="btn btn-danger me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Grupo</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let userCount = 1;
    const usersContainer = document.getElementById('users-container');
    const allUsers = @json($users);

    function addUserSelect() {
        userCount++;

        const div = document.createElement('div');
        div.className = 'mb-3 position-relative';
        div.innerHTML = `
            <div class="d-flex gap-2 align-items-center">
                <div>
                    <select class="form-select user-select" name="users[]">
                    <option value="">Selecione um usuário</option>
                    ${allUsers.map(user => `
                        <option value="${user.id}">${user.name}</option>
                    `).join('')}
                </select>
                </div>
                <div>
                    <button type="button" class="btn btn-sm px-3 rounded-pill btn-outline-danger remove-user">
                        Excluir
                    </button>
                </div>


            </div>
        `;

        usersContainer.appendChild(div);

        const removeButton = div.querySelector('.remove-user');
        const select = div.querySelector('.user-select');

        removeButton.addEventListener('click', function() {
            div.remove();
            updateAvailableOptions();
        });

        select.addEventListener('change', handleSelectChange);
        updateAvailableOptions();
    }

    function handleSelectChange() {
        updateAvailableOptions();

        // Check if this is the last select and has a value
        const allSelects = document.querySelectorAll('.user-select');
        const lastSelect = allSelects[allSelects.length - 1];

        if (this === lastSelect && this.value) {
            addUserSelect();
        }
    }

    function updateAvailableOptions() {
        const selects = document.querySelectorAll('.user-select');
        const selectedValues = Array.from(selects).map(select => select.value).filter(value => value !== '');

        selects.forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(option => {
                if (option.value !== '') {
                    option.disabled = selectedValues.includes(option.value) && option.value !== currentValue;
                }
            });
        });
    }

    // Initialize the first select
    const firstSelect = document.querySelector('.user-select');
    firstSelect.addEventListener('change', handleSelectChange);

    // Adicionar evento no botão de adicionar usuário
    const addUserBtn = document.getElementById('add-user-btn');
    addUserBtn.addEventListener('click', function() {
        addUserSelect();
    });

    // Debug - Log antes do envio do formulário
    document.querySelector('form').addEventListener('submit', function(e) {
        const formData = new FormData(this);
        console.log('=== DADOS DO FORMULÁRIO ===');
        console.log('Action:', this.action);
        console.log('Method:', this.method);

        const usersArray = [];
        for (let [key, value] of formData.entries()) {
            console.log(key, '=', value);
            if (key === 'users[]') {
                usersArray.push(value);
            }
        }

        console.log('Array de usuários:', usersArray);
        console.log('Usuários válidos:', usersArray.filter(u => u && u !== ''));

        const userSelects = document.querySelectorAll('.user-select');
        console.log('Total de selects:', userSelects.length);

        userSelects.forEach((select, index) => {
            console.log(`Select ${index + 1}: ${select.name} = "${select.value}" (${select.value ? 'VÁLIDO' : 'VAZIO'})`);
        });

        // Verificar se pelo menos um usuário foi selecionado
        const validUsers = usersArray.filter(u => u && u !== '');
        if (validUsers.length === 0) {
            e.preventDefault();
            alert('Por favor, selecione pelo menos um usuário para o grupo.');
            return false;
        }

        console.log('Formulário válido, enviando...');
    });
});
</script>

@endsection
