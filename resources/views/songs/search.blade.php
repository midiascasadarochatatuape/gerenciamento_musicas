@extends('layouts.app')

@section('content')
<div class="container">
    <div class="w-100 mb-4 d-flex justify-content-between align-items-center flex-md-row flex-column gap-md-0 gap-4">
        <div class="d-flex flex-shrink-0">
            <h2 class="m-0">Músicas</h2>
        </div>

        <div class="d-flex flex-shrink-0 gap-2 flex-md-row flex-column">
            @if (auth()->check() && auth()->user()->type_user === 'admin')
                @can('create', App\Models\Song::class)
                    <a href="{{ route('songs.create') }}" class="btn btn-sm px-4 rounded-pill btn-primary d-flex align-items-center">Nova Música</a>
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

    <!-- Filtros compactos -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-md-row flex-column-reverse gap-md-0 gap-4 justify-content-between align-items-center">
                <a href="#" id="toggleFilters" class="fw-bold d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined">tune</span>
                    Filtros
                    <span class="material-symbols-outlined chevron-icon">chevron_right</span>
                </a>
                <div class="d-flex gap-2">
                    {{-- <button type="button" id="applyFilters" class="btn btn-primary btn-sm px-4 d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">search</span>
                        Filtrar
                    </button> --}}
                    <button type="button" id="clearFilters" class="btn btn-gray btn-sm px-4 d-flex align-items-center">
                        Limpar filtros
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body filters" style="padding: 0 1.5rem">
            <div class="row g-3 mb-3">
                <div class="col-md-2 col-6">
                    <label class="form-label text-muted">Categoria</label>
                    <select name="category" id="categoryFilter" class="form-select form-select-sm">
                        <option value="">Todas as categorias</option>
                        @foreach($categories->where('type', 'category') as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label text-muted">Tipo</label>
                    <select name="type" id="typeFilter" class="form-select form-select-sm">
                        <option value="">Todos os tipos</option>
                        <option value="cantico">Cântico</option>
                        <option value="hino">Hino</option>
                        <option value="corinho">Corinho</option>
                        <option value="atual">Atual</option>
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label text-muted">Tom</label>
                    <select name="tone" id="toneFilter" class="form-select form-select-sm">
                        <option value="">Todos os tons</option>
                        @php
                            $tones = [
                                'C', 'Cm', 'C#', 'C#m', 'Db', 'Dbm', 'D', 'Dm', 'D#', 'D#m', 'Eb', 'Ebm',
                                'E', 'Em', 'F', 'Fm', 'F#', 'F#m', 'Gb', 'Gbm', 'G', 'Gm', 'G#', 'G#m',
                                'Ab', 'Abm', 'A', 'Am', 'A#', 'A#m', 'Bb', 'Bbm', 'B', 'Bm'
                            ];
                        @endphp
                        @foreach($tones as $tone)
                            <option value="{{ $tone }}">{{ $tone }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label text-muted">Intensidade</label>
                    <select name="intensity" id="intensityFilter" class="form-select form-select-sm">
                        <option value="">Todas as intensidades</option>
                        <option value="lenta">Lenta</option>
                        <option value="media">Média</option>
                        <option value="rapida">Rápida</option>
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label text-muted">Ordenar por</label>
                    <select name="sort" id="sortFilter" class="form-select form-select-sm">
                        <option value="title">Título (A-Z)</option>
                        <option value="title_desc">Título (Z-A)</option>
                        <option value="times">Mais tocadas</option>
                        <option value="times_asc">Menos tocadas</option>
                        <option value="created_at">Mais recentes</option>
                        <option value="created_at_asc">Mais antigas</option>
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label text-muted">Compasso</label>
                    <select name="tempo" id="tempoFilter" class="form-select form-select-sm">
                        <option value="">Todos os compassos</option>
                        <option value="2/4">2/4</option>
                        <option value="3/4">3/4</option>
                        <option value="4/4">4/4</option>
                        <option value="6/8">6/8</option>
                    </select>
                </div>
            </div>

            <!-- Busca alfabética -->
            <div class="border-top py-3">
                <label class="form-label text-muted mb-2">Busca Alfabética</label>
                <div class="d-flex flex-wrap gap-1 justify-content-between">
                    @php
                        $letters = array_merge(range('A', 'Z'), ['#']);
                    @endphp
                    @foreach($letters as $letter)
                        <button type="button" class="alphabet-btn btn btn-sm btn-outline-primary" data-letter="{{ $letter }}" style="min-width: 35px;">
                            {{ $letter }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Contador de músicas -->
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando <span id="currentCount">{{ $songs->count() }}</span> de <span id="totalCount">{{ $totalSongs }}</span> músicas
            </small>
            <div id="loadingIndicator" class="d-none">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <small class="text-muted">Carregando mais músicas...</small>
            </div>
        </div>
    </div>

    <!-- Container das músicas -->
    <div id="songsContainer" class="row row-cols-lg-4 row-cols-md-3 row-cols-2 g-2 position-relative z-0">
        @foreach($songs as $song)
        <div class="col mb-4 song-card">
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
                                    @if($song->measure)
                                        <span class="badge bg-green w-50 d-flex align-items-center justify-content-center gap-1 py-03">
                                            <img src="{{ asset('assets/img/metronomo.svg') }}" height="12" class="text-white" alt="">
                                            {{ $song->measure }}
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

    <!-- Mensagem de fim -->
    <div id="endMessage" class="text-center py-4 {{ $hasMore ? 'd-none' : '' }}">
        <div class="text-muted">
            <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">library_music</span>
            <p class="mb-0">Todas as músicas foram carregadas!</p>
        </div>
    </div>

    <!-- Trigger para scroll infinito -->
    <div id="scrollTrigger" class="{{ !$hasMore ? 'd-none' : '' }}" style="height: 20px;"></div>
</div>

<!-- Modal do YouTube (reutilizar do index) -->
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

<script>
// Funcionalidade de busca com dropdown
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
            const params = new URLSearchParams();
            params.set('search', query);

            fetch(`{{ route('songs.ajax.search') }}?${params.toString()}`, {
                method: 'GET',
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
                searchResults.classList.remove('d-none');
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

// Script principal da página
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let loading = false;
    let hasMore = {{ $hasMore ? 'true' : 'false' }};
    let activeFilters = {};

    // Elementos DOM
    const songsContainer = document.getElementById('songsContainer');
    const scrollTrigger = document.getElementById('scrollTrigger');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const currentCountElement = document.getElementById('currentCount');
    const totalCountElement = document.getElementById('totalCount');
    const endMessage = document.getElementById('endMessage');

    // Filtros
    const toggleButton = document.getElementById('toggleFilters');
    const filtersDiv = document.querySelector('.filters');
    const chevronIcon = toggleButton.querySelector('.chevron-icon');
    const clearFiltersBtn = document.getElementById('clearFilters');    // Configurar Intersection Observer para scroll infinito
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && hasMore && !loading) {
            loadMoreSongs();
        }
    }, {
        threshold: 0.1
    });

    if (hasMore) {
        observer.observe(scrollTrigger);
    }

    // Verificar se há parâmetros na URL para mostrar filtros automaticamente
    function checkUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        let hasParams = false;

        // Verificar filtros básicos
        ['category', 'type', 'tone', 'intensity', 'sort', 'tempo'].forEach(param => {
            if (urlParams.has(param) && urlParams.get(param)) {
                hasParams = true;
                const element = document.getElementById(param + 'Filter');
                if (element) {
                    element.value = urlParams.get(param);
                }
            }
        });

        // Verificar filtro de letra
        if (urlParams.has('letter') && urlParams.get('letter')) {
            hasParams = true;
            const letter = urlParams.get('letter');
            document.querySelectorAll('.alphabet-btn').forEach(btn => {
                if (btn.dataset.letter === letter) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary');
                }
            });
        }

        // Mostrar filtros se há parâmetros
        if (hasParams) {
            setTimeout(() => {
                filtersDiv.classList.add('show');
                chevronIcon.classList.add('rotate');
            }, 100);
        }
    }

    // Executar verificação na inicialização
    checkUrlParams();

    // Função para carregar mais músicas
    async function loadMoreSongs() {
        if (loading || !hasMore) return;

        loading = true;
        loadingIndicator.classList.remove('d-none');

        try {
            const params = new URLSearchParams({
                page: currentPage + 1,
                ...activeFilters
            });

            const response = await fetch(`{{ route('songs.all') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.songs && data.songs.length > 0) {
                data.songs.forEach(song => {
                    const songCard = createSongCard(song);
                    songsContainer.appendChild(songCard);
                });

                currentPage++;
                hasMore = data.hasMore;

                // Atualizar contador
                const newCount = songsContainer.querySelectorAll('.song-card').length;
                currentCountElement.textContent = newCount;

                if (!hasMore) {
                    observer.unobserve(scrollTrigger);
                    scrollTrigger.classList.add('d-none');
                    endMessage.classList.remove('d-none');
                }
            } else {
                hasMore = false;
                observer.unobserve(scrollTrigger);
                scrollTrigger.classList.add('d-none');
                endMessage.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Erro ao carregar músicas:', error);
        } finally {
            loading = false;
            loadingIndicator.classList.add('d-none');
        }
    }

    // Função para criar card da música
    function createSongCard(song) {
        const col = document.createElement('div');
        col.className = 'col mb-4 song-card';

        col.innerHTML = `
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column mb-2">
                        <div class="d-flex justify-content-between border-bottom mb-3 pb-3 gap-2">
                            <div class="d-flex flex-column justify-content-center">
                                <h5 class="card-title m-0">${song.title}</h5>
                                ${song.version ? `<p class="card-subtitle mt-2 text-muted lh-sm small"><strong>${song.version}</strong></p>` : ''}
                            </div>
                            <div class="d-flex justify-content-center align-items-start">
                                <img src="${song.image || '{{ asset('assets/img/default-profile.webp') }}'}" class="foto-perfil" alt="">
                            </div>
                        </div>

                        <div class="mb-2 d-flex align-items-center justify-content-between gap-2">
                            <div class="col-md-9 col-8 d-flex gap-2 flex-column">
                                <div class="d-flex w-100 gap-1">
                                    ${song.tone ? `
                                    <span class="badge bg-blue w-50 d-flex align-items-center justify-content-center gap-1 py-03">
                                        <img src="{{ asset('assets/img/tom.svg') }}" height="12" class="text-white" alt="">
                                        ${song.tone}
                                    </span>` : ''}
                                    ${song.measure ? `
                                    <span class="badge bg-green w-50 d-flex align-items-center justify-content-center gap-1 py-03">
                                        <img src="{{ asset('assets/img/metronomo.svg') }}" height="12" class="text-white" alt="">
                                        ${song.measure}
                                    </span>` : ''}
                                </div>
                                <div class="d-flex w-100 gap-1">
                                    ${song.intensity ? `
                                    <span class="badge bg-primary w-50 d-flex justify-content-center align-items-center gap-1 py-03">
                                        ${getIntensityIcon(song.intensity)}
                                        ${getIntensityLabel(song.intensity)}
                                    </span>` : ''}
                                    ${song.type ? `
                                    <span class="badge bg-primary w-50 d-flex justify-content-center align-items-center gap-1 py-03">
                                        ${getTypeIcon(song.type)}
                                        ${getTypeLabel(song.type)}
                                    </span>` : ''}
                                </div>
                            </div>
                            <div class="col-md-3 col-4 d-flex">
                                ${song.link_youtube ? `
                                <a class="p-0 m-0 w-100 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#youtubeModal" data-youtube-url="${song.link_youtube}">
                                    <span class="material-symbols-outlined play symbol-filled text-danger cursor-pointer mt-1">
                                        play_circle
                                    </span>
                                </a>` : ''}
                            </div>
                        </div>
                        <p class="card-text">
                            <small class="text-muted">Tocada: <strong>${song.times || 0}</strong> vez(es)</small>
                        </p>
                    </div>

                    <div class="d-flex">
                        <a href="/songs/${song.id}" class="btn btn-sm rounded-pill px-4 btn-outline-primary w-100">Ver mais</a>
                    </div>
                </div>
            </div>
        `;

        return col;
    }

    // Funções auxiliares para ícones
    function getIntensityIcon(intensity) {
        const icons = {
            'lenta': '<img src="{{ asset('assets/img/lenta.svg') }}" height="12" width="11" class="text-white" alt="">',
            'media': '<img src="{{ asset('assets/img/media.svg') }}" height="12" width="11" class="text-white" alt="">',
            'rapida': '<img src="{{ asset('assets/img/rapida.svg') }}" height="12" width="11" class="text-white" alt="">'
        };
        return icons[intensity] || '';
    }

    function getIntensityLabel(intensity) {
        const labels = {
            'lenta': 'Lenta',
            'media': 'Média',
            'rapida': 'Rápida'
        };
        return labels[intensity] || intensity;
    }

    function getTypeIcon(type) {
        const icons = {
            'cantico': '<img src="{{ asset('assets/img/cantico.svg') }}" height="12" class="text-white" alt="">',
            'hino': '<img src="{{ asset('assets/img/hino.svg') }}" height="12" class="text-white" alt="">',
            'corinho': '<img src="{{ asset('assets/img/corinho.svg') }}" height="12" class="text-white" alt="">',
            'atual': '<img src="{{ asset('assets/img/atual.svg') }}" height="12" class="text-white" alt="">'
        };
        return icons[type] || '';
    }

    function getTypeLabel(type) {
        const labels = {
            'cantico': 'Cântico',
            'hino': 'Hino',
            'corinho': 'Corinho',
            'atual': 'Atual'
        };
        return labels[type] || type;
    }

    // Toggle filtros
    toggleButton.addEventListener('click', function(e) {
        e.preventDefault();
        filtersDiv.classList.toggle('show');
        chevronIcon.classList.toggle('rotate');
    });

    // Função para coletar filtros ativos
    function collectActiveFilters() {
        activeFilters = {};
        const filterElements = [
            { id: 'categoryFilter', name: 'category' },
            { id: 'typeFilter', name: 'type' },
            { id: 'toneFilter', name: 'tone' },
            { id: 'intensityFilter', name: 'intensity' },
            { id: 'sortFilter', name: 'sort' },
            { id: 'tempoFilter', name: 'tempo' }
        ];

        filterElements.forEach(filter => {
            const element = document.getElementById(filter.id);

            if (element) {
                // Para ordenação, sempre incluir (mesmo se for o padrão)
                if (filter.name === 'sort') {
                    activeFilters[filter.name] = element.value || 'title';
                } else if (element.value && element.value !== '') {
                    // Para outros filtros, só incluir se tiver valor
                    activeFilters[filter.name] = element.value;
                }
            }
        });
    }

    // Aplicar filtros automáticos nos selects
    const selectElements = document.querySelectorAll('.filters select');
    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            // Mostrar os filtros se não estiverem visíveis
            if (!filtersDiv.classList.contains('show')) {
                filtersDiv.classList.add('show');
                chevronIcon.classList.add('rotate');
            }

            // Adicionar feedback visual no select
            this.style.backgroundColor = '#e3f2fd';
            setTimeout(() => {
                this.style.backgroundColor = '';
            }, 1000);

            // Coletar filtros e aplicar
            collectActiveFilters();
            resetAndReload();
        });
    });

    // Limpar filtros
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Limpar selects
            document.querySelectorAll('.filters select').forEach(select => {
                select.value = '';
            });

            // Limpar botões alfabéticos
            document.querySelectorAll('.alphabet-btn').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            // Limpar filtros ativos
            activeFilters = {};

            // Fechar filtros
            if (filtersDiv) {
                filtersDiv.classList.remove('show');
            }
            if (chevronIcon) {
                chevronIcon.classList.remove('rotate');
            }

            // Recarregar sem filtros
            resetAndReload();
        });
    }    // Botões alfabéticos
    document.querySelectorAll('.alphabet-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Mostrar os filtros se não estiverem visíveis
            if (!filtersDiv.classList.contains('show')) {
                filtersDiv.classList.add('show');
                chevronIcon.classList.add('rotate');
            }

            // Toggle ativo
            const isActive = this.classList.contains('btn-primary');

            document.querySelectorAll('.alphabet-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });

            if (!isActive) {
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');

                // Coletar filtros atuais e adicionar letra
                collectActiveFilters();
                activeFilters.letter = this.dataset.letter;
            } else {
                // Se já estava ativo, remover o filtro de letra
                collectActiveFilters();
                delete activeFilters.letter;
            }

            resetAndReload();
        });
    });

    // Função para reset e reload
    async function resetAndReload() {
        // Se já está carregando, não fazer nada
        if (loading) return;

        // Reset estado
        currentPage = 1;
        loading = true;
        hasMore = true;

        // Parar observer anterior se existir
        if (scrollTrigger) {
            observer.unobserve(scrollTrigger);
        }

        // Adicionar efeito de fade out
        songsContainer.style.opacity = '0.3';

        // Esconder mensagem de fim
        endMessage.classList.add('d-none');
        scrollTrigger.classList.remove('d-none');

        // Mostrar loading
        loadingIndicator.classList.remove('d-none');

        try {
            const params = new URLSearchParams({
                page: 1,
                ...activeFilters
            });

            const response = await fetch(`{{ route('songs.all') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // Limpar container após receber dados
            songsContainer.innerHTML = '';

            if (data.songs && data.songs.length > 0) {
                // Adicionar músicas com delay para animação suave
                data.songs.forEach((song, index) => {
                    setTimeout(() => {
                        const songCard = createSongCard(song);
                        songsContainer.appendChild(songCard);
                    }, index * 50); // 50ms delay entre cada card
                });

                hasMore = data.hasMore;
                currentCountElement.textContent = data.songs.length;
                totalCountElement.textContent = data.totalSongs;

                // Restaurar observer se há mais músicas
                if (hasMore) {
                    setTimeout(() => {
                        observer.observe(scrollTrigger);
                    }, data.songs.length * 50 + 100);
                } else {
                    scrollTrigger.classList.add('d-none');
                    setTimeout(() => {
                        endMessage.classList.remove('d-none');
                    }, data.songs.length * 50 + 100);
                }
            } else {
                songsContainer.innerHTML = `
                    <div class="w-100 text-center d-flex justify-content-center py-5">
                        <div class="text-muted d-flex flex-column justify-content-center">
                            <span class="material-symbols-outlined mb-3" style="font-size: 48px; opacity: 0.5;">search_off</span>
                            <p class="mb-0">Nenhuma música encontrada com os filtros aplicados.</p>
                            <button type="button" id="clearFiltersFromEmpty" class="btn btn-outline-primary btn-sm mt-3 d-flex align-items-center align-self-center">
                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">clear</span>
                                Limpar Filtros
                            </button>
                        </div>
                    </div>
                `;

                // Adicionar event listener para o botão de limpar da mensagem vazia
                setTimeout(() => {
                    const clearFromEmptyBtn = document.getElementById('clearFiltersFromEmpty');
                    if (clearFromEmptyBtn) {
                        clearFromEmptyBtn.addEventListener('click', () => {
                            clearFiltersBtn.click();
                        });
                    }
                }, 100);

                hasMore = false;
                scrollTrigger.classList.add('d-none');
                currentCountElement.textContent = '0';
                if (data.totalSongs !== undefined) {
                    totalCountElement.textContent = data.totalSongs;
                }
            }

            // Restaurar opacidade
            setTimeout(() => {
                songsContainer.style.opacity = '1';
            }, 200);

        } catch (error) {
            console.error('Erro ao filtrar músicas:', error);
            songsContainer.innerHTML = `
                <div class="w-100 text-center d-flex justify-content-center py-5">
                    <div class="text-danger d-flex flex-column justify-content-center">
                        <span class="material-symbols-outlined mb-3" style="font-size: 48px; opacity: 0.5;">error</span>
                        <p class="mb-2">Erro ao carregar as músicas.</p>
                        <button type="button" onclick="location.reload()" class="btn btn-outline-danger btn-sm d-flex align-items-center align-self-center">
                            <span class="material-symbols-outlined me-1" style="font-size: 16px;">refresh</span>
                            Tentar Novamente
                        </button>
                    </div>
                </div>
            `;
            songsContainer.style.opacity = '1';
            hasMore = false;
            scrollTrigger.classList.add('d-none');
        } finally {
            loading = false;
            loadingIndicator.classList.add('d-none');
        }
    }

    // YouTube modal (reutilizar código do index)
    const youtubeModal = document.getElementById('youtubeModal');
    const youtubeIframe = document.getElementById('youtubeIframe');

    youtubeModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const youtubeUrl = button.getAttribute('data-youtube-url');
        const videoId = getYoutubeVideoId(youtubeUrl);
        const embedUrl = `https://www.youtube.com/embed/${videoId}`;
        youtubeIframe.src = embedUrl;
    });

    youtubeModal.addEventListener('hide.bs.modal', function() {
        youtubeIframe.src = '';
    });

    function getYoutubeVideoId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
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

.alphabet-btn {
    transition: all 0.2s ease;
}

.alphabet-btn:hover {
    transform: translateY(-1px);
}

#loadingIndicator {
    transition: opacity 0.3s ease;
}

.song-card {
    animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#endMessage {
    transition: all 0.3s ease;
}

/* Transições suaves para o container */
#songsContainer {
    transition: opacity 0.3s ease;
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

/* Melhorar aparência dos botões alfabéticos */
.alphabet-btn.btn-primary {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

/* Estilos para o searchForm */
.searchdiv {
    width: calc(100% - 2rem);
    max-height: 300px;
    overflow-y: auto;
    border-radius: 0.5rem;
    border: 1px solid rgba(0,0,0,0.1);
    z-index: 1000;
}

.search-item:hover {
    background-color: #f8f9fa;
}

.searchspinner {
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    display: none;
}
</style>

@endsection
