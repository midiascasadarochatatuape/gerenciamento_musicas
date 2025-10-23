@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <h2>Sugerir Nova Música</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" value="1">

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="title">Título *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="version">Versão</label>
                            <input type="text" class="form-control @error('version') is-invalid @enderror"
                                   id="version" name="version" value="{{ old('version') }}">
                            @error('version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="link_youtube">Link do YouTube</label>
                            <input type="url" class="form-control @error('link_youtube') is-invalid @enderror"
                                   id="link_youtube" name="link_youtube" value="{{ old('link_youtube') }}">
                            @error('link_youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="link_spotify">Link do Spotify</label>
                            <input type="url" class="form-control @error('link_spotify') is-invalid @enderror"
                                   id="link_spotify" name="link_spotify" value="{{ old('link_spotify') }}">
                            @error('link_spotify')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="lyrics">Letra da Música</label>
                            <textarea required class="form-control @error('lyrics') is-invalid @enderror"
                                      id="lyrics" name="lyrics" rows="9">{{ old('lyrics') }}</textarea>
                            @error('lyrics')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="d-flex flex-lg-row flex-column-reverse justify-content-center gap-4">
                    <a href="{{ route('songs.suggest') }}" class="btn px-4 rounded-pill btn-danger">Cancelar</a>
                    <button type="submit" formaction="{{ route('songs.store') }}" class="btn px-4 rounded-pill btn-primary d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">send</span>
                        Enviar Sugestão
                    </button>
                    <button type="submit" formaction="{{ route('songs.store-suggestion-and-new') }}" class="btn px-4 rounded-pill btn-success d-flex align-items-center">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">add_circle</span>
                        Salvar e Criar Nova
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
