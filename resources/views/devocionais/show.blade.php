@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="row">
        <div class="col-12 mx-auto">
            <!-- Cabeçalho -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <h1 class="display-5 mb-0">{{ $devocional->title }}</h1>
                    </div>
                    <div class="d-flex gap-2">
                        @if($devocional->is_published)
                            <span class="badge bg-success">Publicado</span>
                        @else
                            <span class="badge bg-warning">Rascunho</span>
                        @endif
                    </div>
                </div>

                <div class="text-muted d-flex flex-wrap gap-3 mb-3">
                    <span>
                        <i class="fas fa-calendar"></i>
                        {{ $devocional->devotional_date ? $devocional->devotional_date->format('d/m/Y') : 'Data não definida' }}
                    </span>
                    <span>
                        <i class="fas fa-user"></i>
                        {{ $devocional->user->name }}
                    </span>
                    <span>
                        <i class="fas fa-eye"></i>
                        {{ $devocional->views }} visualizações
                    </span>
                    <span>
                        <i class="fas fa-clock"></i>
                        Criado em {{ $devocional->created_at ? $devocional->created_at->format('d/m/Y') : 'Data não disponível' }}
                    </span>
                </div>                @if($devocional->bible_references && count($devocional->bible_references) > 0)
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-book-open"></i>
                            Referências Bíblicas
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($devocional->bible_references as $reference)
                                <span class="badge bg-primary">{{ $reference }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Imagem -->
            @if($devocional->image)
                <div class="mb-4">
                    <img src="{{ Storage::url($devocional->image) }}"
                         alt="{{ $devocional->title }}"
                         class="img-fluid rounded shadow">
                </div>
            @endif

            <!-- Conteúdo -->
            <div class="mb-4">
                <div class="content-area">
                    {!! $devocional->content !!}
                </div>
            </div>

            <!-- Ações -->
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('devocionais.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Voltar para Lista
                </a>

                <div class="d-flex gap-4">
                    @can('update', $devocional)
                        <a href="{{ route('devocionais.edit', $devocional) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    @endcan

                    @can('delete', $devocional)
                        <form method="POST" action="{{ route('devocionais.destroy', $devocional) }}"
                              class="d-inline"
                              onsubmit="return confirm('Tem certeza que deseja excluir este devocional?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </form>
                    @endcan

                    @if($devocional->is_published)
                        <a href="{{ route('devocionais.public.show', $devocional) }}"
                           class="btn btn-success text-white" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Ver Público
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-area {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #2c3e50;
    }

    .content-area h1,
    .content-area h2,
    .content-area h3,
    .content-area h4,
    .content-area h5,
    .content-area h6 {
        color: #2c3e50;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .content-area p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .content-area blockquote {
        border-left: 4px solid #3498db;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0 0.25rem 0.25rem 0;
    }

    .content-area ul,
    .content-area ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .content-area li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
