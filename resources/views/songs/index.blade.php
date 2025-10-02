@extends('layouts.app')

@section('content')
<div class="container">
    <div class="w-100 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex flex-shrink-0">
            <h2 class="m-0">Músicas</h2>
        </div>

        <div class="d-flex flex-shrink-0 gap-2">
            <a href="{{ route('songs.all') }}" class="btn btn-sm px-4 rounded-pill btn-outline-primary d-flex align-items-center">
                <span class="material-symbols-outlined me-1" style="font-size: 16px;">view_stream</span>
                Ver todas as músicas
            </a>
            @if (auth()->check() && auth()->user()->type_user === 'admin')
                @can('create', App\Models\Song::class)
                    <a href="{{ route('songs.create') }}" class="btn btn-sm px-4 rounded-pill btn-primary">Nova Música</a>
                @endcan
            @endif
        </div>
    </div>

    <!-- Busca sempre visível -->
    <div class="card mb-4 position-relative" style="z-index: 10;">
        <div class="card-body">
            <form id="searchForm" class="form-group position-relative m-0">
                @csrf
                <input type="text" class="form-control rounded-pill" id="search" name="search" placeholder="Buscar por título, autor ou referência bíblica..." autocomplete="off">
                <div id="searchResults" class="position-absolute px-3 shadow mt-1 bg-white d-none searchdiv z-10"></div>
                <div id="searchSpinner" class="position-absolute searchspinner">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Buscando...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Filtros avançados -->
    <div class="card mb-5 position-relative" style="z-index: 5;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a href="#" id="toggleFilters" class="fw-bold d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined">tune</span>
                    Filtros Avançados
                    <span class="material-symbols-outlined chevron-icon">chevron_right</span>
                </a>
                <div class="d-flex gap-2">
                    <button type="submit" form="filterForm" class="btn btn-primary btn-sm px-4 d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">search</span>
                        Filtrar
                    </button>
                    <a href="{{ route('songs.index') }}" class="btn btn-outline-secondary btn-sm px-4 d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">clear</span>
                        Limpar
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body filters" style="padding: 0 1.5rem">
            <form id="filterForm" action="{{ route('songs.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <!-- Primeira linha de filtros -->
                    <div class="col-md-2">
                        <label class="form-label text-muted">Categoria</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">Todas as categorias</option>
                            @foreach($categories->where('type', 'category') as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-muted">Tipo</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">Todos os tipos</option>
                            <option value="cantico" {{ request('type') == 'cantico' ? 'selected' : '' }}>Cântico</option>
                            <option value="hino" {{ request('type') == 'hino' ? 'selected' : '' }}>Hino</option>
                            <option value="corinho" {{ request('type') == 'corinho' ? 'selected' : '' }}>Corinho</option>
                            <option value="atual" {{ request('type') == 'atual' ? 'selected' : '' }}>Atual</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-muted">Tom</label>
                        <select name="tone" class="form-select form-select-sm">
                            <option value="">Todos os tons</option>
                            @php
                                $tones = ['C', 'C#', 'Db', 'D', 'D#', 'Eb', 'E', 'F', 'F#', 'Gb', 'G', 'G#', 'Ab', 'A', 'A#', 'Bb', 'B'];
                            @endphp
                            @foreach($tones as $tone)
                                <option value="{{ $tone }}" {{ request('tone') == $tone ? 'selected' : '' }}>{{ $tone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-muted">Intensidade</label>
                        <select name="intensity" class="form-select form-select-sm">
                            <option value="">Todas as intensidades</option>
                            <option value="lenta" {{ request('intensity') == 'lenta' ? 'selected' : '' }}>Lenta</option>
                            <option value="media" {{ request('intensity') == 'media' ? 'selected' : '' }}>Média</option>
                            <option value="rapida" {{ request('intensity') == 'rapida' ? 'selected' : '' }}>Rápida</option>
                        </select>
                    </div>


                <!-- Segunda linha de filtros -->

                    <div class="col-md-2">
                        <label class="form-label text-muted">Ordenar por</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Título (A-Z)</option>
                            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Título (Z-A)</option>
                            <option value="times" {{ request('sort') == 'times' ? 'selected' : '' }}>Mais tocadas</option>
                            <option value="times_asc" {{ request('sort') == 'times_asc' ? 'selected' : '' }}>Menos tocadas</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mais recentes</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Mais antigas</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label text-muted">Compasso</label>
                        <select name="tempo" class="form-select form-select-sm">
                            <option value="">Todos os compassos</option>
                            <option value="2/4" {{ request('tempo') == '2/4' ? 'selected' : '' }}>2/4</option>
                            <option value="3/4" {{ request('tempo') == '3/4' ? 'selected' : '' }}>3/4</option>
                            <option value="4/4" {{ request('tempo') == '4/4' ? 'selected' : '' }}>4/4</option>
                            <option value="6/8" {{ request('tempo') == '6/8' ? 'selected' : '' }}>6/8</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Busca alfabética -->
            <div class="border-top py-4">
                <label class="form-label text-muted mb-2">Busca Alfabética</label>
                <div class="d-flex flex-wrap gap-1 justify-content-between">
                    @php
                        $letters = array_merge(range('A', 'Z'), ['#']);
                        $currentLetter = request('letter', '');
                    @endphp

                    @foreach($letters as $letter)
                        <a href="{{ route('songs.index', array_merge(request()->except('letter'), ['letter' => $letter])) }}"
                           class="alphabet btn btn-sm {{ $currentLetter === $letter ? 'btn-primary' : 'btn-outline-primary' }}"
                           style="min-width: 35px;">
                            {{ $letter }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <div class="row row-cols-lg-5 row-cols-md-3 col row-cols-1 position-relative z-0">
        @foreach($songs as $song)
        <div class="col mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column mb-2">
                        <div class="d-flex justify-content-between border-bottom mb-3 pb-3 gap-2">
                            <div class="d-flex flex-column justify-content-center">
                                <h5 class="card-title m-0">{{ $song->title }}</h5>
                                @if($song->version)
                                    <p class="card-subtitle mt-2 text-muted lh-sm small"><strong>{{ $song->version }}</strong></p>
                                @endif
                            </div>
                            <div class="d-flex justify-content-center align-items-start">
                                @if($song->image)
                                    <img src="{{ $song->image }}" class="foto-perfil" alt="">
                                @else
                                    <img src="{{ asset('assets/img/default-profile.webp') }}" class="foto-perfil" alt="">
                                @endif
                            </div>
                        </div>


                        <div class="mb-2 d-flex align-items-center justify-content-between gap-2">
                            <div class="col-md-9 col-8 d-flex gap-2 flex-column">
                                <div class="d-flex w-100 gap-1">
                                    @if($song->tone)
                                    <span class="badge bg-blue w-50 d-flex align-items-center justify-content-center gap-1 py-03">
                                        <img src="{{ asset('assets/img/tom.svg') }}" height="12" class="text-white" alt="">
                                        {{ $song->tone }}
                                    </span>
                                    @endif
                                    @if($song->tempo)
                                        <span class="badge bg-green w-50 d-flex align-items-center justify-content-center gap-1 py-03">
                                            <img src="{{ asset('assets/img/metronomo.svg') }}" height="12" class="text-white" alt="">
                                            {{ $song->tempo }}
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex w-100 gap-1">
                                    @if($song->intensity)
                                    <span class="badge bg-primary w-50 d-flex justify-content-center align-items-center gap-1 py-03">
                                        @if ($song->intensity == 'lenta')
                                            <img src="{{ asset('assets/img/lenta.svg') }}" height="12" width="11" class="text-white" alt="">
                                            Lenta
                                        @elseif ($song->intensity == 'media')
                                            <img src="{{ asset('assets/img/media.svg') }}" height="12" width="11" class="text-white" alt="">
                                            Média
                                        @else
                                            <img src="{{ asset('assets/img/rapida.svg') }}" height="12" width="11" class="text-white" alt="">
                                            Rápida
                                        @endif
                                    </span>
                                    @endif
                                    @if($song->type)
                                        <span class="badge bg-primary w-50 d-flex justify-content-center align-items-center gap-1 py-03">
                                            @if ($song->type == 'cantico')
                                                <img src="{{ asset('assets/img/cantico.svg') }}" height="12" class="text-white" alt="">
                                                Cântico
                                            @elseif ($song->type == 'hino')
                                                <img src="{{ asset('assets/img/hino.svg') }}" height="12" class="text-white" alt="">
                                                Hino
                                            @elseif ($song->type == 'corinho')
                                                <img src="{{ asset('assets/img/corinho.svg') }}" height="12" class="text-white" alt="">
                                                Corinho
                                            @else
                                                <img src="{{ asset('assets/img/atual.svg') }}" height="12" class="text-white" alt="">
                                                Atual
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-4 d-flex">
                                @if($song->link_youtube)
                                <a class="p-0 m-0 w-100 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#youtubeModal" data-youtube-url="{{ $song->link_youtube }}">
                                    <span class="material-symbols-outlined play symbol-filled text-danger cursor-pointer mt-1">
                                        play_circle
                                    </span>
                                </a>
                                @endif
                            </div>
                        </div>
                        <p class="card-text">
                            <small class="text-muted">Tocada: <strong>{{ $song->times }}</strong> vez(es)</small>
                        </p>
                    </div>

                    <div class="d-flex">
                        <a href="{{ route('songs.show', $song) }}" class="btn btn-sm rounded-pill px-4 btn-outline-primary w-100">Ver mais</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $songs->links('layouts.pagination') }}
    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('search');
    const searchResults = document.getElementById('searchResults');
    const searchSpinner = document.getElementById('searchSpinner');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('d-none');
            searchSpinner.style.display = 'none';
            return;
        }

        searchSpinner.style.display = 'block';

        timeoutId = setTimeout(() => {
            const formData = new FormData(searchForm);
            formData.set('search', query);

            fetch(`{{ route('songs.search') }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na busca');
                }
                return response.json();
            })
            .then(data => {
                searchResults.innerHTML = '';
                searchResults.classList.remove('d-none');

                if (data.length === 0) {
                    searchResults.innerHTML = '<div class="p-3">Nenhuma música encontrada</div>';
                    return;
                }

                data.forEach(song => {
                    const div = document.createElement('div');
                    div.className = 'p-3 border-bottom search-item';
                    div.innerHTML = `
                        <a href="/songs/${song.id}" class="text-decoration-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">${song.title}</h6>
                                    ${song.version ? `<small class="text-muted">${song.version}</small>` : ''}
                                </div>
                                ${song.biblical_reference ? `<small class="badge bg-secondary">${song.biblical_reference}</small>` : ''}
                            </div>
                        </a>
                    `;
                    searchResults.appendChild(div);
                });
            })
            .catch(error => {
                searchResults.innerHTML = '<div class="p-3 text-danger">Erro ao realizar a busca</div>';
                console.error('Erro:', error);
            })
            .finally(() => {
                searchSpinner.style.display = 'none';
            });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchForm.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });
});
</script>

<!-- Modal do YouTube -->
<div class="modal fade" id="youtubeModal" tabindex="-1" aria-labelledby="youtubeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="youtubeModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <iframe id="youtubeIframe" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adicione este código no seu bloco de script existente -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('search');
    const searchResults = document.getElementById('searchResults');
    const searchSpinner = document.getElementById('searchSpinner');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('d-none');
            searchSpinner.style.display = 'none';
            return;
        }

        searchSpinner.style.display = 'block';

        timeoutId = setTimeout(() => {
            const formData = new FormData(searchForm);
            formData.set('search', query);

            fetch(`{{ route('songs.search') }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na busca');
                }
                return response.json();
            })
            .then(data => {
                searchSpinner.style.display = 'none';
                searchResults.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(song => {
                        const div = document.createElement('div');
                        div.className = 'p-2 border-bottom border-1 border-lighter search-item';
                        div.style.cursor = 'pointer';
                        div.innerHTML = `
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${song.title}</strong>
                                    ${song.version ? `<small class="text-muted"> - ${song.version}</small>` : ''}
                                </div>
                                <div>
                                    <small class="text-muted">${song.tone || ''}</small>
                                </div>
                            </div>
                        `;
                        div.addEventListener('click', () => {
                            window.location.href = `/songs/${song.id}`;
                        });
                        searchResults.appendChild(div);
                    });
                    searchResults.classList.remove('d-none');
                } else {
                    searchResults.innerHTML = '<div class="p-2 text-muted">Nenhuma música encontrada</div>';
                    searchResults.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Erro na busca:', error);
                searchSpinner.style.display = 'none';
                searchResults.innerHTML = '<div class="p-2 text-danger">Erro ao realizar a busca</div>';
                searchResults.classList.remove('d-none');
            });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });
});

// Adicione este código para manipular o modal do YouTube
document.addEventListener('DOMContentLoaded', function() {
    const youtubeModal = document.getElementById('youtubeModal');
    const youtubeIframe = document.getElementById('youtubeIframe');

    youtubeModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const youtubeUrl = button.getAttribute('data-youtube-url');

        // Converte a URL do YouTube para formato de incorporação
        const videoId = getYoutubeVideoId(youtubeUrl);
        const embedUrl = `https://www.youtube.com/embed/${videoId}`;
        youtubeIframe.src = embedUrl;
    });

    youtubeModal.addEventListener('hide.bs.modal', function() {
        youtubeIframe.src = ''; // Limpa o iframe quando o modal é fechado
    });

    // Função para extrair o ID do vídeo da URL do YouTube
    function getYoutubeVideoId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Código para o toggle dos filtros
    const toggleButton = document.getElementById('toggleFilters');
    const filtersDiv = document.querySelector('.filters');
    const chevronIcon = toggleButton.querySelector('.chevron-icon');

    // Função para verificar se há parâmetros de busca na URL
    function hasSearchParams() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.has('category') ||
               urlParams.has('type') ||
               urlParams.has('tone') ||
               urlParams.has('intensity') ||
               urlParams.has('sort') ||
               urlParams.has('tempo') ||
               urlParams.has('letter');
    }

    // Mostrar filtros se houver parâmetros de busca
    if (hasSearchParams()) {
        // Adicionar um delay pequeno para permitir que a página carregue
        setTimeout(() => {
            filtersDiv.classList.add('show');
            chevronIcon.classList.add('rotate');
        }, 100);
    }

    toggleButton.addEventListener('click', function(e) {
        e.preventDefault();

        if (filtersDiv.classList.contains('show')) {
            // Fechando: remover classe e deixar CSS fazer a transição
            filtersDiv.classList.remove('show');
            chevronIcon.classList.remove('rotate');
            // Limpar qualquer style inline que possa interferir
            filtersDiv.style.opacity = '';
        } else {
            // Abrindo: adicionar classe show primeiro para iniciar expansão
            filtersDiv.classList.add('show');
            chevronIcon.classList.add('rotate');
            // Limpar qualquer style inline que possa interferir
            filtersDiv.style.opacity = '';
        }
    });

    // Adicionar evento ao botão Limpar para fechar os filtros
    const clearButtons = document.querySelectorAll('a[href="{{ route("songs.index") }}"]');
    clearButtons.forEach(button => {
        button.addEventListener('click', function() {
            filtersDiv.classList.remove('show');
            chevronIcon.classList.remove('rotate');
        });
    });

    // Filtros AJAX em tempo real (sem recarregar página)
    const filterForm = document.getElementById('filterForm');
    const songsContainer = document.querySelector('.row.row-cols-lg-5');
    const paginationContainer = document.querySelector('.d-flex.justify-content-center');

    if (filterForm) {
        const selectElements = filterForm.querySelectorAll('select');

        // Função para aplicar filtros via AJAX
        function applyFiltersAjax() {
            // Mostrar loading global
            showGlobalLoading();

            // Coletar todos os valores dos filtros
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value && value !== '') {
                    params.append(key, value);
                }
            }

            // Manter parâmetros da URL atual (como letter)
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.forEach((value, key) => {
                if (!['category', 'type', 'tone', 'intensity', 'sort', 'tempo'].includes(key)) {
                    if (value && value !== '') {
                        params.append(key, value);
                    }
                }
            });

            // Fazer requisição AJAX
            fetch(`{{ route('songs.index') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Criar um documento temporário para parsear o HTML
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // Extrair o conteúdo das músicas
                const newSongsContainer = tempDiv.querySelector('.row.row-cols-lg-5');
                const newPaginationContainer = tempDiv.querySelector('.d-flex.justify-content-center');

                if (newSongsContainer) {
                    // Atualizar o container das músicas com animação suave
                    songsContainer.style.opacity = '0.3';
                    setTimeout(() => {
                        songsContainer.innerHTML = newSongsContainer.innerHTML;
                        songsContainer.style.opacity = '1';

                        // Atualizar paginação se existir
                        if (newPaginationContainer && paginationContainer) {
                            paginationContainer.innerHTML = newPaginationContainer.innerHTML;
                        }

                        // Atualizar URL sem recarregar
                        const newUrl = `${window.location.pathname}?${params.toString()}`;
                        window.history.pushState({}, '', newUrl);

                        hideGlobalLoading();
                    }, 200);
                }
            })
            .catch(error => {
                console.error('Erro nos filtros AJAX:', error);
                hideGlobalLoading();
                // Fallback: recarregar página se AJAX falhar
                filterForm.submit();
            });
        }

        // Função para mostrar loading global
        function showGlobalLoading() {
            if (!document.getElementById('globalLoading')) {
                const loadingDiv = document.createElement('div');
                loadingDiv.id = 'globalLoading';
                loadingDiv.innerHTML = `
                    <div class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.8); z-index: 9999;">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden">Filtrando...</span>
                            </div>
                            <div class="text-primary fw-semibold">Filtrando músicas...</div>
                        </div>
                    </div>
                `;
                document.body.appendChild(loadingDiv);
            }
        }

        // Função para esconder loading global
        function hideGlobalLoading() {
            const loadingDiv = document.getElementById('globalLoading');
            if (loadingDiv) {
                loadingDiv.remove();
            }
        }

        // Aplicar eventos aos selects
        selectElements.forEach(select => {
            select.addEventListener('change', function() {
                // Mostrar os filtros se não estiverem visíveis
                if (!filtersDiv.classList.contains('show')) {
                    filtersDiv.classList.add('show');
                    chevronIcon.classList.add('rotate');
                }

                // Aplicar filtros via AJAX
                applyFiltersAjax();
            });
        });

        // Interceptar cliques na paginação para AJAX
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const link = e.target.closest('a');
                const url = new URL(link.href);

                // Fazer requisição AJAX para paginação
                showGlobalLoading();
                fetch(url.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    const newSongsContainer = tempDiv.querySelector('.row.row-cols-lg-5');
                    const newPaginationContainer = tempDiv.querySelector('.d-flex.justify-content-center');

                    if (newSongsContainer) {
                        songsContainer.style.opacity = '0.3';
                        setTimeout(() => {
                            songsContainer.innerHTML = newSongsContainer.innerHTML;
                            songsContainer.style.opacity = '1';

                            if (newPaginationContainer && paginationContainer) {
                                paginationContainer.innerHTML = newPaginationContainer.innerHTML;
                            }

                            window.history.pushState({}, '', url.href);
                            hideGlobalLoading();

                            // Scroll suave para o topo
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }, 200);
                    }
                })
                .catch(error => {
                    console.error('Erro na paginação AJAX:', error);
                    hideGlobalLoading();
                    window.location.href = link.href;
                });
            }
        });

        // Interceptar cliques nos links alfabéticos para AJAX
        document.addEventListener('click', function(e) {
            if (e.target.closest('.alphabet')) {
                e.preventDefault();
                const link = e.target.closest('a');
                const url = new URL(link.href);

                // Fazer requisição AJAX para busca alfabética
                showGlobalLoading();
                fetch(url.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    const newSongsContainer = tempDiv.querySelector('.row.row-cols-lg-5');
                    const newPaginationContainer = tempDiv.querySelector('.d-flex.justify-content-center');
                    const newAlphabetContainer = tempDiv.querySelector('.d-flex.flex-wrap.gap-1');

                    if (newSongsContainer) {
                        songsContainer.style.opacity = '0.3';
                        setTimeout(() => {
                            songsContainer.innerHTML = newSongsContainer.innerHTML;
                            songsContainer.style.opacity = '1';

                            if (newPaginationContainer && paginationContainer) {
                                paginationContainer.innerHTML = newPaginationContainer.innerHTML;
                            }

                            // Atualizar botões alfabéticos para mostrar o ativo
                            if (newAlphabetContainer) {
                                const currentAlphabetContainer = document.querySelector('.d-flex.flex-wrap.gap-1');
                                if (currentAlphabetContainer) {
                                    currentAlphabetContainer.innerHTML = newAlphabetContainer.innerHTML;
                                }
                            }

                            window.history.pushState({}, '', url.href);
                            hideGlobalLoading();
                        }, 200);
                    }
                })
                .catch(error => {
                    console.error('Erro na busca alfabética AJAX:', error);
                    hideGlobalLoading();
                    window.location.href = link.href;
                });
            }
        });
    }
});
</script>

<style>
.filters {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out 0.1s, opacity 0.2s ease-out, transform 0.2s ease-out;
    opacity: 0;
    transform: translateY(-10px);
}

.filters.show {
    max-height: 500px;
    transition: max-height 0.4s ease-in, opacity 0.3s ease-in 0.15s, transform 0.3s ease-in 0.15s;
    opacity: 1;
    transform: translateY(0);
}

.chevron-icon {
    transition: transform 0.3s ease;
}

.chevron-icon.rotate {
    transform: rotate(90deg);
}

.alphabet {
    transition: all 0.2s ease;
}

.alphabet:hover {
    transform: translateY(-1px);
}

.form-label.small {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.searchspinner {
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    display: none;
}

.searchdiv {
    width: calc(100% - 2rem);
    max-height: 300px;
    overflow-y: auto;
    border-radius: 0.5rem;
    border: 1px solid rgba(0,0,0,0.1);
}

.search-item:hover {
    background-color: #f8f9fa;
}

/* Loading states para os selects */
select:disabled {
    background-color: #f8f9fa;
    opacity: 0.6;
    cursor: wait;
}

.loading-select {
    position: relative;
}

.loading-select::after {
    content: '';
    position: absolute;
    right: 30px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid #dee2e6;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 10;
    pointer-events: none;
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}

/* Transições suaves para AJAX */
.row.row-cols-lg-5 {
    transition: opacity 0.3s ease;
}

#globalLoading {
    backdrop-filter: blur(2px);
}

#globalLoading .spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>

@endsection
