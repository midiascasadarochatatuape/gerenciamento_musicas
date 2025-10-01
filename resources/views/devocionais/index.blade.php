@extends('layouts.app')

@section('content')
<div class="container">
    <div class="w-100 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex flex-shrink-0">
            <h2 class="m-0">Devocionais</h2>
        </div>

        <div class="d-flex flex-shrink-0">
            @can('create', App\Models\Devocional::class)
                <a href="{{ route('devocionais.create') }}" class="btn btn-sm px-4 rounded-pill btn-primary">
                    Novo Devocional
                </a>
            @endcan
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('devocionais.index') }}" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por título ou conteúdo..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Todos os status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>
                            Publicados
                        </option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>
                            Rascunhos
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('devocionais.index') }}" class="btn btn-outline-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Devocionais -->
    <div class="row">
        @forelse($devocionais as $devocional)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($devocional->image)
                        <img src="{{ Storage::url($devocional->image) }}" class="card-img-top" alt="{{ $devocional->title }}" style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $devocional->title }}</h5>
                            <div>
                                @if($devocional->is_published)
                                    <span class="badge bg-success">Publicado</span>
                                @else
                                    <span class="badge bg-warning">Rascunho</span>
                                @endif
                            </div>
                        </div>

                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-calendar"></i> {{ $devocional->devotional_date ? $devocional->devotional_date->format('d/m/Y') : 'Data não definida' }} |
                            <i class="fas fa-user"></i> {{ $devocional->user->name }} |
                            <i class="fas fa-eye"></i> {{ $devocional->views }} visualizações
                        </p>

                        <p class="card-text flex-grow-1">{{ $devocional->excerpt }}</p>

                        @if($devocional->bible_references && count($devocional->bible_references) > 0)
                            <div class="mb-2">
                                <small class="text-primary">
                                    <i class="fas fa-book-open"></i>
                                    {{ implode(', ', $devocional->bible_references) }}
                                </small>
                            </div>
                        @endif

                        <div class="mt-auto">
                            <div class="d-flex gap-2 justify-content-between" role="group">
                                <div>
                                    <a href="{{ route('devocionais.show', $devocional) }}" class="btn px-4 btn-primary btn-sm">Ver</a>
                                @can('update', $devocional)
                                    <a href="{{ route('devocionais.edit', $devocional) }}" class="btn px-4 btn-outline-primary btn-sm">Editar</a>
                                @endcan
                                </div>

                                @can('delete', $devocional)
                                    <form method="POST" action="{{ route('devocionais.destroy', $devocional) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este devocional?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger px-4 btn-sm">
                                            Excluir
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>Nenhum devocional encontrado</h4>
                    <p>Não há devocionais cadastrados ainda.</p>
                    @can('create', App\Models\Devocional::class)
                        <a href="{{ route('devocionais.create') }}" class="btn btn-primary">
                            Criar Primeiro Devocional
                        </a>
                    @endcan
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $devocionais->withQueryString()->links() }}
    </div>
</div>
@endsection
