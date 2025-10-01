@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar Escala</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('schedule.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="group_id">Grupo *</label>
                            <select class="form-control form-select @error('group_id') is-invalid @enderror"
                                    id="group_id" name="group_id" required>
                                <option value="">Selecione um grupo</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('group_id', $schedule->group_id) == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Data *</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                   id="date" name="date" value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="time">Horário</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror"
                                   id="time" name="time" value="{{ old('time', $schedule->time ? $schedule->time->format('H:i') : '') }}">
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="event_type">Tipo de Evento</label>
                            <input type="text" class="form-control @error('event_type') is-invalid @enderror"
                                   id="event_type" name="event_type" value="{{ old('event_type', $schedule->event_type) }}">
                            @error('event_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label>Músicas *</label>
                        <div id="songs-container">
                            @foreach($schedule->songs as $index => $scheduledSong)
                                <div class="song-item mb-2">
                                    <div class="row">
                                        <div class="col-md-6 d-flex align-items-center">
                                            <span class="material-symbols-outlined handle">drag_indicator</span>
                                            <select class="form-control form-select me-4" name="songs[]" required>
                                                <option value="">Selecione uma música</option>
                                                @foreach($songs as $song)
                                                    <option value="{{ $song->id }}"
                                                        {{ $scheduledSong->id == $song->id ? 'selected' : '' }}>
                                                        {{ $song->title }}
                                                    </option>
                                                @endforeach
                                            </select>

                                                <button type="button" class="btn btn-outline-danger btn-sm px-3 remove-song">
                                                    Remover
                                                </button>

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-success btn-sm my-3 text-white" id="add-song">
                            + Adicionar Música
                        </button>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes">Observações Gerais</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $schedule->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('schedule.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Atualizar Escala</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const songsContainer = document.getElementById('songs-container');
    const addSongButton = document.getElementById('add-song');
    const allSongs = @json($songs);
    let selectedSongs = [];

    // Função para atualizar os selects, desabilitando músicas já selecionadas
    function updateSongSelects() {
        // Obter todas as músicas selecionadas atualmente
        selectedSongs = [];
        document.querySelectorAll('select[name="songs[]"]').forEach(select => {
            if (select.value) {
                selectedSongs.push(select.value);
            }
        });

        // Atualizar cada select para desabilitar músicas já selecionadas
        document.querySelectorAll('select[name="songs[]"]').forEach(select => {
            const currentValue = select.value;

            // Reconstruir as opções
            select.innerHTML = '<option value="">Selecione uma música</option>';

            allSongs.forEach(song => {
                const option = document.createElement('option');
                option.value = song.id;
                option.textContent = song.title;

                // Se esta música já está selecionada em outro select, desabilite-a
                if (selectedSongs.includes(String(song.id)) && String(song.id) !== currentValue) {
                    option.disabled = true;
                }

                // Se esta é a música selecionada neste select, selecione-a
                if (String(song.id) === currentValue) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        });
    }

    function addSongSelect() {
        const div = document.createElement('div');
        div.className = 'song-item mb-2';
        div.innerHTML = `

            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <span class="material-symbols-outlined handle">drag_indicator</span>
                    <select class="form-control form-select me-4" name="songs[]" required>
                        <option value="">Selecione uma música</option>
                        ${allSongs.map(song => `
                            <option value="${song.id}">${song.title}</option>
                        `).join('')}
                    </select>
                    <button type="button" class="btn btn-outline-danger px-3 btn-sm remove-song">
                        Remover
                    </button>
                </div>
            </div>
        `;

        songsContainer.appendChild(div);

        // Atualizar os selects após adicionar um novo
        updateSongSelects();

        // Adicionar evento de change para o novo select
        const newSelect = div.querySelector('select');
        newSelect.addEventListener('change', updateSongSelects);
    }

    // Adicionar evento de change para os selects existentes
    document.querySelectorAll('select[name="songs[]"]').forEach(select => {
        select.addEventListener('change', updateSongSelects);
    });

    // Inicializar os selects
    updateSongSelects();

    // Adicionar um evento de submit ao formulário para garantir que a ordem seja respeitada
    document.querySelector('form').addEventListener('submit', function(e) {
        // Obter todos os selects na ordem atual após possível reordenamento
        const selects = Array.from(document.querySelectorAll('select[name="songs[]"]'));
        
        // Remover os selects do formulário (vamos readicioná-los na ordem correta)
        selects.forEach(select => {
            select.name = '';
        });
        
        // Readicionar os selects na ordem correta
        selects.forEach((select, index) => {
            select.name = `songs[${index}]`;
        });
    });

    // Função para inicializar/reinicializar o Sortable
    function initSortable() {
        if (sortable) {
            sortable.destroy();
        }
        
        sortable = new Sortable(songsContainer, {
            animation: 150,
            handle: '.handle',
            onEnd: function() {
                // Obter a nova ordem das músicas (apenas selects com valores válidos)
                const newOrder = Array.from(document.querySelectorAll('select[name="songs[]"]'))
                    .filter(select => select.value) // Filtrar apenas selects com valores
                    .map(select => select.value);

                // Verificar se há músicas para reordenar
                if (newOrder.length === 0) {
                    console.error('Nenhuma música selecionada para reordenar');
                    return;
                }

                // Enviar a nova ordem para o servidor
                fetch('{{ route("schedule.update-order", $schedule) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        songs: newOrder
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta do servidor: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        console.log('Ordem atualizada com sucesso!');
                    } else {
                        console.error('Erro ao atualizar a ordem:', data.message || 'Erro desconhecido');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar a ordem:', error);
                });
            }
        });
    }

    // Inicializar o Sortable pela primeira vez
    let sortable = null;
    initSortable();

    addSongButton.addEventListener('click', function() {
        addSongSelect();
        // Atualizar o Sortable após adicionar uma nova música
        initSortable();
    });

    songsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-song')) {
            e.target.closest('.song-item').remove();
            // Atualizar os selects após remover um
            updateSongSelects();
            
            // Atualizar o Sortable após remover uma música
            initSortable();
        }
    });
});
</script>

<!-- Adicionar CSS para o ícone de arrastar -->
<style>
.handle {
    cursor: move;
    margin-right: 10px;
}
</style>
@endsection
