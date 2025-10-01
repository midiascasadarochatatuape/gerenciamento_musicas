@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar Grupo</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('group.update', $group) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nome do Grupo</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $group->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="users-container" class="d-flex flex-column">
                    @foreach($selectedUsers as $index => $selectedUser)
                        <div class="mb-3">
                            <div class="d-flex gap-2">
                                <div>
                                    <select class="form-select user-select" name="users[]" required>
                                        <option value="">Selecione um usuário</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ $selectedUser->id == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    @if(!$loop->first)
                                        <button type="button" class="btn btn-outline-danger remove-user">
                                            Excluir
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-secondary text-white" id="add-user">
                        + Adicionar membro
                    </button>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('group.index') }}" class="btn btn-danger me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Atualizar Grupo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let userCount = {{ $selectedUsers->count() }};
    const usersContainer = document.getElementById('users-container');
    const allUsers = @json($users);

    function addUserSelect() {
        userCount++;

        const div = document.createElement('div');
        div.className = 'col-md-3 mb-3 position-relative';
        div.innerHTML = `
            <div class="d-flex gap-2">
                <select class="form-select user-select" name="users[]">
                    <option value="">Selecione um usuário</option>
                    ${allUsers.map(user => `
                        <option value="${user.id}">${user.name}</option>
                    `).join('')}
                </select>
                <button type="button" class="btn btn-outline-danger remove-user">
                    Excluir
                </button>
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

    // Initialize all selects and remove buttons
    document.querySelectorAll('.user-select').forEach(select => {
        select.addEventListener('change', handleSelectChange);
    });

    document.querySelectorAll('.remove-user').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.mb-3').remove();
            updateAvailableOptions();
        });
    });

    // Add button event listener
    const addButton = document.getElementById('add-user');
    addButton.addEventListener('click', function() {
        addUserSelect();
    });

    // Initial update of available options
    updateAvailableOptions();
});
</script>
@endsection
