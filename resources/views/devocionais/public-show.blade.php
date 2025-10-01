@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
            <p class="text-muted mb-0">
                <i class="fas fa-user-circle"></i>
                Por <strong>{{ $devocional->user->name }}</strong> em
                {{ $devocional->created_at ? $devocional->created_at->format('d/m/Y') : 'Data não disponível' }}
            </p>
        </div>
    <div class="row">
        <div class="col-lg-8">
            <!-- Navegação -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('devocionais.public.index') }}">Devocionais</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ Str::limit($devocional->title, 50) }}
                    </li>
                </ol>
            </nav>

            <!-- Artigo -->
            <article>
                <header class="mb-4">
                    <h1 class="display-5 mb-3">{{ $devocional->title }}</h1>

                    <div class="text-muted d-flex flex-wrap gap-3 mb-4">
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
                    </div>                    @if($devocional->bible_references && count($devocional->bible_references) > 0)
                        <div class="alert alert-primary">
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
                </header>

                @if($devocional->image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($devocional->image) }}"
                             alt="{{ $devocional->title }}"
                             class="img-fluid rounded shadow">
                    </div>
                @endif

                <div class="content-area">
                    {!! $devocional->content !!}
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Devocionais Recentes -->
                @if($recentDevocionais->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock"></i>
                                Devocionais Recentes
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($recentDevocionais as $recent)
                                <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                    <h6 class="mb-1">
                                        <a href="{{ route('devocionais.public.show', $recent) }}"
                                           class="text-decoration-none">
                                            {{ $recent->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i>
                                        {{ $recent->devotional_date ? $recent->devotional_date->format('d/m/Y') : 'Data não definida' }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('devocionais.public.index') }}"
                               class="btn btn-outline-primary btn-sm">
                                Ver Todos os Devocionais
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Compartilhar
    document.getElementById('shareBtn').addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $devocional->title }}',
                text: '{{ $devocional->excerpt }}',
                url: window.location.href
            });
        } else {
            // Fallback: copiar para clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                // Mudar temporariamente o texto do botão
                const btn = document.getElementById('shareBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-success');

                setTimeout(function() {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            });
        }
    });
</script>
@endpush
