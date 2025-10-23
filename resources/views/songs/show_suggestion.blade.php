@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>{{ $song->title }}</h2>
        </div>
        <div class="col text-end">
            <div class="btn-group">
                @can('update', $song)
                    <button type="button" class="btn btn-sm px-3 rounded-pill btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        Status: {{ $song->status == 1 ? 'Sugerida' :
                                  ($song->status == 2 ? 'Em Análise' :
                                  ($song->status == 5 ? 'Stand By' :
                                  ($song->status == 3 ? 'Reprovada' :
                                  ($song->status == 7 ? 'Aprovada' : 'Publicada')))) }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ $song->status == 1 ? 'active' : '' }}" href="#"
                            onclick="event.preventDefault(); document.getElementById('status-1').submit();">Sugerida</a></li>
                        <li><a class="dropdown-item {{ $song->status == 2 ? 'active' : '' }}" href="#"
                            onclick="event.preventDefault(); document.getElementById('status-2').submit();">Em Análise</a></li>
                        <li><a class="dropdown-item {{ $song->status == 5 ? 'active' : '' }}" href="#"
                            onclick="event.preventDefault(); document.getElementById('status-5').submit();">Stand By</a></li>
                        <li><a class="dropdown-item {{ $song->status == 3 ? 'active' : '' }}" href="#"
                            onclick="event.preventDefault(); document.getElementById('status-3').submit();">Reprovada</a></li>
                        <li><a class="dropdown-item {{ $song->status == 7 ? 'active' : '' }}" href="#"
                            onclick="event.preventDefault(); document.getElementById('status-7').submit();">Aprovada</a></li>
                    </ul>
                @endcan

                <div class="ms-2 d-flex gap-2">
                    @can('update', $song)
                        <a href="{{ route('songs.edit', $song) }}" class="btn px-3 btn-sm rounded-pill btn-outline-primary">
                            Editar
                        </a>
                    @endcan
                    <a href="{{ route('songs.suggest') }}" class="btn px-3 btn-sm rounded-pill btn-outline-primary">
                        Voltar
                    </a>

                </div>
            </div>

            @for($i = 1; $i <= 7; $i++)
                <form id="status-{{ $i }}" action="{{ route('songs.update', $song) }}" method="POST" class="d-none">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $i }}">
                    <input type="hidden" name="title" value="{{ $song->title }}">
                    <input type="hidden" name="version" value="{{ $song->version }}">
                    <input type="hidden" name="tone" value="{{ $song->tone }}">
                    <input type="hidden" name="tempo" value="{{ $song->tempo }}">
                    <input type="hidden" name="measure" value="{{ $song->measure }}">
                    <input type="hidden" name="snippet" value="{{ $song->snippet }}">
                    <input type="hidden" name="link_youtube" value="{{ $song->link_youtube }}">
                    <input type="hidden" name="link_spotify" value="{{ $song->link_spotify }}">
                </form>
            @endfor
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        @if($song->version)
                            <h5>Versão: {{ $song->version }}</h5>
                        @endif
                        <div class="d-flex gap-2 mb-2">
                            @if($song->tone)
                                <span class="badge bg-primary">{{ $song->tone }}</span>
                            @endif
                            @if($song->tempo)
                                <span class="badge bg-info">{{ $song->tempo }} BPM</span>
                            @endif
                            @if($song->measure)
                                <span class="badge bg-secondary">{{ $song->measure }}</span>
                            @endif
                        </div>
                    </div>

                    @if($song->snippet)
                        <div class="mb-4">
                            <h5>Trecho da Música</h5>
                            <div class="card">
                                <div class="card-body px-0">
                                    <pre class="mb-0 h5" style="font-family: sans-serif">{{ $song->snippet }}</pre>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($song->lyrics)
                        <div class="mb-4">
                            <h5>Letra</h5>
                            <pre class="lyrics">{{ $song->lyrics }}</pre>
                        </div>
                    @endif

                    @if($song->chords)
                        <div class="mb-4">
                            <h5>Cifra</h5>
                            <pre class="chords">{{ $song->chords }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">informações adicionais</h5>
                    <p>Tocada: <strong>{{ $song->times }}</strong> vez(es)</p>
                    @if($song->link_youtube || $song->link_spotify)
                        <div class="d-flex flex-column gap-2">
                            @if($song->link_youtube)
                                <a href="{{ $song->link_youtube }}" target="_blank" class="btn btn-danger d-flex align-items-center justify-content-center gap-2">
                                    <span class="material-symbols-outlined text-white m-0">smart_display</span>
                                    <span>Veja no YouTube</span>
                                </a>
                            @endif
                            @if($song->link_spotify)
                                <a href="{{ $song->link_spotify }}" target="_blank" class="btn btn-green text-white d-flex align-items-center justify-content-center gap-2">
                                    <span class="material-symbols-outlined text-white m-0">play_circle</span>
                                    <span>Escute no Spotify</span>
                                </a>
                            @endif
                            @if($song->link_drive)
                            <a href="" target="_blank" class="btn btn-blue text-white d-flex align-items-center justify-content-center gap-2">
                                <span class="material-symbols-outlined text-white">drive_export</span>
                                <span>Kit de vozes</span>
                            </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @can('delete', $song)
        <form action="{{ route('songs.destroy', $song) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn px-3 btn-sm rounded-pill btn-outline-danger"
                    onclick="return confirm('Tem certeza que deseja excluir esta sugestão?')">
                <i class="bi bi-trash"></i> Excluir
            </button>
        </form>
    @endcan
</div>
@endsection
