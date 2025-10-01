@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="display-4">Devocionais</h1>
    </div>

    <!-- Busca -->
    <div class="row mb-4 d-none">
        <div class="col-md-8 mx-auto">
            <form method="GET" action="{{ route('devocionais.public.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-lg"
                           placeholder="Buscar devocionais..."
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('devocionais.public.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Devocionais -->
    @if(request('search'))
        <div class="mb-3">
            <p class="text-muted">
                <i class="fas fa-search"></i>
                Resultados para: <strong>"{{ request('search') }}"</strong>
                ({{ $devocionais->total() }} {{ $devocionais->total() === 1 ? 'resultado' : 'resultados' }})
            </p>
        </div>
    @endif

    <div class="row">
        @forelse($devocionais as $devocional)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($devocional->image)
                        <img src="{{ Storage::url($devocional->image) }}"
                             class="card-img-top"
                             alt="{{ $devocional->title }}"
                             style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('devocionais.public.show', $devocional) }}"
                               class="text-decoration-none text-dark">
                                {{ $devocional->title }}
                            </a>
                        </h5>

                        <div class="text-muted small mb-3">
                            <i class="fas fa-calendar"></i> {{ $devocional->devotional_date ? $devocional->devotional_date->format('d/m/Y') : 'Data não definida' }} |
                            <i class="fas fa-eye"></i> {{ $devocional->views }} visualizações
                        </div>

                        <p class="card-text flex-grow-1">{{ $devocional->excerpt }}</p>

                        @if($devocional->bible_references && count($devocional->bible_references) > 0)
                            <div class="mb-3">
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($devocional->bible_references as $reference)
                                        <span class="badge bg-secondary small">{{ $reference }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-auto">
                            <a href="{{ route('devocionais.public.show', $devocional) }}"
                               class="btn btn-primary btn-sm px-4">
                                Ler Devocional <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h3>{{ request('search') ? 'Nenhum resultado encontrado' : 'Nenhum devocional disponível' }}</h3>
                    <p class="text-muted">
                        {{ request('search')
                           ? 'Tente usar palavras-chave diferentes.'
                           : 'Os devocionais em breve estarão disponíveis.' }}
                    </p>
                    @if(request('search'))
                        <a href="{{ route('devocionais.public.index') }}" class="btn btn-primary">
                            Ver Todos os Devocionais
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    @if($devocionais->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $devocionais->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .card-title a:hover {
        color: #007bff !important;
    }
</style>
@endpush
