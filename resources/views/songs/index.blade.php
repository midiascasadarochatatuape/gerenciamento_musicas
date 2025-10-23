@extends('layouts.app')

@section('content')
<div class="container">
    <div class="w-100 mb-4 d-flex justify-content-between align-items-center flex-md-row flex-column gap-md-0 gap-4">
        <div class="d-flex flex-shrink-0">
            <h2 class="m-0">Músicas</h2>
        </div>

        <div class="d-flex flex-shrink-0 gap-2 flex-md-row flex-column">
            <a href="{{ route('songs.index') }}" class="btn btn-sm px-4 rounded-pill btn-outline-primary d-flex align-items-center">
                <span class="material-symbols-outlined me-1" style="font-size: 16px;">search</span>
                Busca Avançada
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
            <div class="d-flex flex-md-row flex-column-reverse gap-md-0 gap-4 justify-content-between align-items-center">
                <a href="#" id="toggleFilters" class="fw-bold d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined">tune</span>
                    Filtros Avançados
                    <span class="material-symbols-outlined chevron-icon">chevron_right</span>
                </a>
                <div class="d-flex gap-2">
                    <button type="button" id="applyFiltersBtn" class="btn btn-primary btn-sm px-4 d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">search</span>
                        Filtrar
                    </button>
                    <button type="button" id="clearFiltersBtn" class="btn btn-outline-secondary btn-sm px-4 d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">clear</span>
                        Limpar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body filters" style="padding: 0 1.5rem">
            <form id="filterForm" action="{{ route('songs.simple') }}" method="GET" class="mb-4">
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
                                $tones = [
                                    'C', 'Cm', 'C#', 'C#m', 'Db', 'Dbm', 'D', 'Dm', 'D#', 'D#m', 'Eb', 'Ebm',
                                    'E', 'Em', 'F', 'Fm', 'F#', 'F#m', 'Gb', 'Gbm', 'G', 'Gm', 'G#', 'G#m',
                                    'Ab', 'Abm', 'A', 'Am', 'A#', 'A#m', 'Bb', 'Bbm', 'B', 'Bm'
                                ];
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
                        <a href="{{ route('songs.simple', array_merge(request()->except('letter'), ['letter' => $letter])) }}"
                           class="alphabet btn btn-sm {{ $currentLetter === $letter ? 'btn-primary' : 'btn-outline-primary' }}"
                           style="min-width: 35px;">
                            {{ $letter }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <div class="row row-cols-lg-5 row-cols-md-3 g-2 row-cols-2 position-relative z-0">
        @foreach($songs as $song)
        <div class="col mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column mb-2">
                        <div class="d-flex justify-content-between border-bottom mb-3 pb-3 gap-2">
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{ route('songs.show', $song) }}" class="card-title m-0 link-primary h5">{{ $song->title }}</a>
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

    <!-- Contador de músicas -->
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Mostrando <span id="currentCount">{{ $songs->count() }}</span> de <span id="totalCount">{{ $songs->total() }}</span> músicas
            </small>
            <div id="loadingIndicator" class="d-none">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <small class="text-muted">Carregando mais músicas...</small>
            </div>
        </div>
    </div>

    <!-- Mensagem de fim -->
    <div id="endMessage" class="text-center py-4 {{ $songs->hasMorePages() ? 'd-none' : '' }}">
        <div class="text-muted">
            <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">library_music</span>
            <p class="mb-0">Todas as músicas foram carregadas!</p>
        </div>
    </div>

    <!-- Trigger para scroll infinito -->
    <div id="scrollTrigger" class="{{ !$songs->hasMorePages() ? 'd-none' : '' }}" style="height: 20px;"></div></div>


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
    // Variáveis para scroll infinito
    let currentPage = {{ $songs->currentPage() }};
    let loading = false;
    let hasMore = {{ $songs->hasMorePages() ? 'true' : 'false' }};
    let activeFilters = {};

    // Elementos DOM
    const songsContainer = document.querySelector('.row.row-cols-lg-5');
    const scrollTrigger = document.getElementById('scrollTrigger');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const currentCountElement = document.getElementById('currentCount');
    const totalCountElement = document.getElementById('totalCount');
    const endMessage = document.getElementById('endMessage');

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



    // Configurar Intersection Observer para scroll infinito
    const observer = new IntersectionObserver((entries) => {
        console.log('👀 Observer:', {
            intersecting: entries[0].isIntersecting,
            hasMore,
            loading
        });

        if (entries[0].isIntersecting && hasMore && !loading) {
            loadMoreSongs();
        }
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    // Inicializar observers
    if (hasMore && scrollTrigger) {
        observer.observe(scrollTrigger);
    }

    // Scroll listener principal (mais confiável que Intersection Observer)
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        if (scrollTimeout) clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const scrollPosition = window.innerHeight + window.scrollY;
            const documentHeight = document.documentElement.offsetHeight;

            // Se chegou próximo ao fim da página (200px antes)
            if (scrollPosition >= documentHeight - 200 && hasMore && !loading) {
                loadMoreSongs();
            }
        }, 150);
    });    // Função para carregar mais músicas
    window.loadMoreSongs = async function() {
        if (loading || !hasMore) return;

        loading = true;
        loadingIndicator.classList.remove('d-none');        try {
            const params = new URLSearchParams({
                page: currentPage + 1,
                ...activeFilters
            });

            const response = await fetch(`{{ route('songs.simple') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.songs && data.songs.length > 0) {
                data.songs.forEach((song, index) => {
                    setTimeout(() => {
                        const songCard = createSongCard(song);
                        songsContainer.appendChild(songCard);
                    }, index * 50);
                });

                currentPage++;
                hasMore = data.hasMore;

                // Atualizar contador
                const newCount = songsContainer.querySelectorAll('.col').length;
                currentCountElement.textContent = newCount;

                if (!hasMore) {
                    observer.unobserve(scrollTrigger);
                    scrollTrigger.classList.add('d-none');
                    setTimeout(() => {
                        endMessage.classList.remove('d-none');
                    }, data.songs.length * 50 + 100);
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
        col.className = 'col mb-4';

        col.innerHTML = `
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column mb-2">
                        <div class="d-flex justify-content-between border-bottom mb-3 pb-3 gap-2">
                            <div class="d-flex flex-column justify-content-center">
                                <a href='{{ route('songs.show', $song) }}' class="card-title m-0 link-primary h5">${song.title}</a>
                                \${song.version ? `<p class="card-subtitle mt-2 text-muted lh-sm small"><strong>\${song.version}</strong></p>` : ''}
                            </div>
                            <div class="d-flex justify-content-center align-items-start">
                                <img src="\${song.image || '{{ asset('assets/img/default-profile.webp') }}'}" class="foto-perfil" alt="">
                            </div>
                        </div>

                        <div class="mb-2 d-flex align-items-center justify-content-between gap-2">
                            <div class="col-md-9 col-8 d-flex gap-2 flex-column">
                                <div class="d-flex w-100 gap-1">
                                    \${song.tone ? `
                                    <span class="badge bg-blue w-50 d-flex align-items-center justify-content-center gap-1 py-03">
                                        <img src="{{ asset('assets/img/tom.svg') }}" height="12" class="text-white" alt="">
                                        \${song.tone}
                                    </span>` : ''}
                                    \${song.measure ? `
                                    <span class="badge bg-green w-50 d-flex align-items-center justify-content-center gap-1 py-03">
                                        <img src="{{ asset('assets/img/metronomo.svg') }}" height="12" class="text-white" alt="">
                                        \${song.measure}
                                    </span>` : ''}
                                </div>
                                <div class="d-flex w-100 gap-1">
                                    \${song.intensity ? `
                                    <span class="badge bg-primary w-50 d-flex justify-content-center align-items-center gap-1 py-03">
                                        \${getIntensityIcon(song.intensity)}
                                        \${getIntensityLabel(song.intensity)}
                                    </span>` : ''}
                                    \${song.type ? `
                                    <span class="badge bg-primary w-50 d-flex justify-content-center align-items-center gap-1 py-03">
                                        \${getTypeIcon(song.type)}
                                        \${getTypeLabel(song.type)}
                                    </span>` : ''}
                                </div>
                            </div>
                            <div class="col-md-3 col-4 d-flex">
                                \${song.link_youtube ? `
                                <a class="p-0 m-0 w-100 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#youtubeModal" data-youtube-url="\${song.link_youtube}">
                                    <span class="material-symbols-outlined play symbol-filled text-danger cursor-pointer mt-1">
                                        play_circle
                                    </span>
                                </a>` : ''}
                            </div>
                        </div>
                        <p class="card-text">
                            <small class="text-muted">Tocada: <strong>\${song.times || 0}</strong> vez(es)</small>
                        </p>
                    </div>

                    <div class="d-flex">
                        <a href="/songs/\${song.id}" class="btn btn-sm rounded-pill px-4 btn-outline-primary w-100">Ver mais</a>
                    </div>
                </div>
            </div>
        `;

        return col;
    }

    // Funções auxiliares para ícones (copiadas da página all)
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

    // Filtros AJAX em tempo real (sem recarregar página)
    const filterForm = document.getElementById('filterForm');

    if (filterForm) {
        const selectElements = filterForm.querySelectorAll('select');

        // Função para aplicar filtros via AJAX (resetando scroll infinito)
        function applyFiltersAjax(filters) {
            // Se já está carregando, não fazer nada
            if (loading) return;

            // Atualizar filtros ativos
            activeFilters = filters;

            // Reset estado do scroll infinito
            currentPage = 1;
            loading = true;
            hasMore = true;

            // Parar observer anterior
            if (scrollTrigger) {
                observer.unobserve(scrollTrigger);
            }

            // Mostrar loading global
            showGlobalLoading();

            // Criar parâmetros da URL
            const params = new URLSearchParams();
            Object.entries(activeFilters).forEach(([key, value]) => {
                if (value && value !== '') {
                    params.append(key, value);
                }
            });

            // Fazer requisição AJAX
            fetch(`{{ route('songs.simple') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Limpar container
                songsContainer.innerHTML = '';

                // Esconder mensagem de fim
                endMessage.classList.add('d-none');
                scrollTrigger.classList.remove('d-none');

                if (data.songs && data.songs.length > 0) {
                    // Adicionar músicas com delay
                    data.songs.forEach((song, index) => {
                        setTimeout(() => {
                            const songCard = createSongCard(song);
                            songsContainer.appendChild(songCard);
                        }, index * 50);
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
                        <div class="col-12 text-center py-5">
                            <div class="text-muted">
                                <span class="material-symbols-outlined mb-3" style="font-size: 48px; opacity: 0.5;">search_off</span>
                                <p class="mb-0">Nenhuma música encontrada com os filtros aplicados.</p>
                                <button type="button" onclick="document.getElementById('clearFiltersBtn').click()" class="btn btn-outline-primary btn-sm mt-3">
                                    Limpar Filtros
                                </button>
                            </div>
                        </div>
                    `;
                    hasMore = false;
                    scrollTrigger.classList.add('d-none');
                    currentCountElement.textContent = '0';
                }

                hideGlobalLoading();
            })
            .catch(error => {
                console.error('Erro nos filtros AJAX:', error);
                hideGlobalLoading();
                songsContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <div class="text-danger">
                            <span class="material-symbols-outlined mb-3" style="font-size: 48px; opacity: 0.5;">error</span>
                            <p class="mb-2">Erro ao filtrar as músicas.</p>
                            <button type="button" onclick="location.reload()" class="btn btn-outline-danger btn-sm">
                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">refresh</span>
                                Tentar Novamente
                            </button>
                        </div>
                    </div>
                `;
                hasMore = false;
                scrollTrigger.classList.add('d-none');
            })
            .finally(() => {
                loading = false;
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

        // Função para coletar filtros ativos
        function collectActiveFilters() {
            const activeFilters = {};
            const filterElements = [
                { selector: 'select[name="category"]', name: 'category' },
                { selector: 'select[name="type"]', name: 'type' },
                { selector: 'select[name="tone"]', name: 'tone' },
                { selector: 'select[name="intensity"]', name: 'intensity' },
                { selector: 'select[name="sort"]', name: 'sort' },
                { selector: 'select[name="tempo"]', name: 'tempo' }
            ];

            filterElements.forEach(filter => {
                const element = filterForm.querySelector(filter.selector);
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

            // Manter parâmetros da URL atual (como letter)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('letter') && urlParams.get('letter')) {
                activeFilters.letter = urlParams.get('letter');
            }

            return activeFilters;
        }

        // Aplicar eventos aos selects para auto-submissão
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

                // Aplicar filtros via AJAX
                const activeFilters = collectActiveFilters();
                applyFiltersAjax(activeFilters);
            });
        });

        // Event listeners para botões de filtro
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');

        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', function() {
                const activeFilters = collectActiveFilters();
                applyFiltersAjax(activeFilters);
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                // Limpar todos os selects
                filterForm.querySelectorAll('select').forEach(select => {
                    select.value = '';
                });

                // Fechar filtros
                filtersDiv.classList.remove('show');
                chevronIcon.classList.remove('rotate');

                // Recarregar página limpa
                window.location.href = '{{ route("songs.simple") }}';
            });
        }

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

                // Mostrar filtros se não estiverem visíveis
                if (!filtersDiv.classList.contains('show')) {
                    filtersDiv.classList.add('show');
                    chevronIcon.classList.add('rotate');
                }

                // Coletar filtros atuais e adicionar letra
                const activeFilters = collectActiveFilters();
                const letter = url.searchParams.get('letter');
                if (letter) {
                    activeFilters.letter = letter;
                }

                // Atualizar botões alfabéticos visualmente
                document.querySelectorAll('.alphabet').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });

                if (letter) {
                    link.classList.remove('btn-outline-primary');
                    link.classList.add('btn-primary');
                }

                // Aplicar filtros via AJAX
                applyFiltersAjax(activeFilters);
            }
        });
    }

    // ==========================================
    // INICIALIZAÇÃO DO SCROLL INFINITO
    // ==========================================

    // Observer já foi inicializado acima

        // Função de debug temporária
    window.debugScrollInfinito = function() {
        console.log('🔍 Debug Estado:', {
            currentPage,
            hasMore,
            loading,
            totalSongs: totalCountElement?.textContent,
            currentCount: currentCountElement?.textContent,
            scrollTriggerExists: !!scrollTrigger,
            observerActive: hasMore && scrollTrigger
        });
        alert(`Debug: Página ${currentPage}, hasMore: ${hasMore}, loading: ${loading}, Total: ${totalCountElement?.textContent}`);
    };

    // Scroll infinito inicializado
    console.log('🎵 Estado inicial:', {
        currentPage,
        hasMore,
        loading,
        totalCount: totalCountElement?.textContent
    });
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
