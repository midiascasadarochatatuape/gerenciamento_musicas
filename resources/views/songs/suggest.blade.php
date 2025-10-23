@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-3">
            <h2>Sugestões</h2>
        </div>
        <div class="col-9 text-end">
            <a href="{{ route('songs.create.suggestion') }}" class="btn btn-sm px-4 btn-primary rounded-pill">Sugerir Nova Música</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link link-primary {{ request('status', 1) == 1 ? 'active' : '' }}"
                               href="{{ route('songs.suggest', ['status' => 1]) }}">
                               Sugeridas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary {{ request('status') == 2 ? 'active' : '' }}"
                               href="{{ route('songs.suggest', ['status' => 2]) }}">
                               Em Análise
                            </a>
                        </li>
                        @if (auth()->user()->type_user == 'admin')


                        <li class="nav-item">
                            <a class="nav-link link-primary {{ request('status') == 3 ? 'active' : '' }}"
                               href="{{ route('songs.suggest', ['status' => 3]) }}">
                               Reprovadas
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link link-primary {{ request('status') == 5 ? 'active' : '' }}"
                               href="{{ route('songs.suggest', ['status' => 5]) }}">
                               Stand By
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="row row-cols-lg-5 row-cols-md-3 row-cols-1">
                        @forelse($songs as $song)
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


                                        <div class="mb-2 d-flex align-items-center justify-content-center gap-2">
                                            <div class="col-md-9 col-8 d-flex gap-2 flex-column d-none">
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
                                                            {{ $song->tempo }} {{ $song->measure }}
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
                                            <div class="col-md-3 col-4 d-flex justify-content-center">
                                                @if($song->link_youtube)
                                                <a class="p-0 m-0 w-100 d-flex justify-content-center align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#youtubeModal" data-youtube-url="{{ $song->link_youtube }}">
                                                    <span class="material-symbols-outlined play symbol-filled text-danger cursor-pointer mt-1">
                                                        play_circle
                                                    </span>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <a href="{{ route('songs.show', $song) }}" class="btn btn-sm rounded-pill px-4 btn-outline-primary w-100">Ver mais</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info p-2">
                                Nenhuma música encontrada.
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $songs->links('layouts.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal do YouTube -->
<div class="modal fade" id="youtubeModal" tabindex="-1" aria-labelledby="youtubeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="youtubeModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="youtubeIframe" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
@endsection
