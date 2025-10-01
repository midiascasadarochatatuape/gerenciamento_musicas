@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header da página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-primary">{{ __('Visão Geral') }}</h2>
                <div class="text-primary fw-semibold">
                    <i class="fas fa-clock me-1"></i>
                    {{ now()->format('d/m/Y - H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Principal - ESCALAS (maior destaque) -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Próximas Escalas
                    </h5>
                    <a href="{{ route('schedules.index') }}" class="btn btn-success text-white btn-sm">
                        <i class="fas fa-eye me-1"></i>Ver Todas
                    </a>
                </div>
                <div class="card-body row p-4">
                    @if(!$nextUserSchedule)
                        <div class="alert alert-info alert-sm mb-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Você não tem escalas programadas.
                        </div>
                    @endif

                    @forelse($nextSchedules as $schedule)
                        <div class="col-md-6 mt-2 py-2
                        {{
                            $loop->count % 2 == 0
                                ? ($loop->remaining > 1 ? 'border-bottom' : '')
                                : (!$loop->last ? 'border-bottom' : '')
                        }}
                        {{ $loop->iteration % 2 == 1 ? 'border-end' : '' }}">
                            <div class="row align-items-center px-3">
                                <div class="col-md-8 col-8">
                                    <div class="d-flex mb-2">
                                        <h4 class="mb-0 text-secondary">{{ $schedule->group->name }}</h4>
                                        @if(in_array($schedule->id, $userScheduleIds ?? []))
                                            <a href="{{ route('schedule.user') }}" class="ms-3">
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-user me-1"></i>Sua escala
                                                </span>
                                            </a>
                                        @endif
                                    </div>

                                    @if($schedule->event_name)
                                        <h6 class="mb-2 text-dark">{{ $schedule->event_name }}</h6>
                                    @endif
                                    @if($schedule->description)
                                        <p class="text-muted mb-3">{{ $schedule->description }}</p>
                                    @endif

                                    <div class="d-flex text-muted flex-column align-items-start justify-content-center">
                                        <div>
                                            <i class="fas fa-calendar me-2"></i>
                                            <strong>{{ $schedule->date->format('d/m/Y') }}</strong>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock me-2"></i>
                                            <strong>{{ $schedule->time ? $schedule->formatted_time : 'Horário não definido' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-4 text-end">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="badge bg-secondary fs-6 mb-2 px-3 py-2">
                                            {{ $schedule->date->format('d') }}
                                            <div class="small">
                                                @php
                                                    $meses = [
                                                        'Jan' => 'Jan', 'Feb' => 'Fev', 'Mar' => 'Mar', 'Apr' => 'Abr',
                                                        'May' => 'Mai', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago',
                                                        'Sep' => 'Set', 'Oct' => 'Out', 'Nov' => 'Nov', 'Dec' => 'Dez'
                                                    ];
                                                    $mesIngles = $schedule->date->format('M');
                                                @endphp
                                                {{ $meses[$mesIngles] ?? $mesIngles }}
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            @php
                                                $now = now();
                                                $today = $now->startOfDay(); // Início do dia atual
                                                $eventDate = $schedule->date->startOfDay(); // Início do dia do evento

                                                // Calcular diferença apenas em dias (ignorando horas)
                                                $daysDiff = $today->diffInDays($eventDate, false);

                                                if ($daysDiff > 0) {
                                                    // Evento no futuro
                                                    $timeText = 'em ' . $daysDiff . ' dia' . ($daysDiff > 1 ? 's' : '');
                                                } elseif ($daysDiff < 0) {
                                                    // Evento no passado
                                                    $daysDiff = abs($daysDiff);
                                                    $timeText = 'há ' . $daysDiff . ' dia' . ($daysDiff > 1 ? 's' : '');
                                                } else {
                                                    // Mesmo dia
                                                    $timeText = 'hoje';
                                                }
                                            @endphp
                                            {{ $timeText }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted mb-3">Nenhuma escala agendada</h5>
                            <p class="text-muted mb-4">As próximas escalas aparecerão aqui quando forem criadas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- MÚSICAS POPULARES (terceiro destaque - menor) -->
        <div class="col-lg-4 mb-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">

                        Últimas Músicas Executadas
                    </h5>
                    <span class="">
                        <i class="fas fa-music me-2" style="font-size: 1.75rem"></i>
                    </span>
                </div>
                <div class="card-body py-0 px-4">
                    @forelse($topSongs as $song)
                        <div class="d-flex my-2 py-2 justify-content-between align-items-center {{
                            $loop->count % 2 == 0
                                ? ($loop->remaining > 1 ? 'border-bottom' : '')
                                : (!$loop->last ? 'border-bottom' : '')
                        }}">
                            <a class="flex-grow-1 me-2 py-1" href="{{ route('songs.show', $song) }}">
                                <h5 class="mb-0 text-truncate">{{ $song->title }}</h5>
                                <p class="text-muted mb-0">{{ $song->version ?? 'Versão original' }}</p>
                            </a>
                            <p class="text-white h6 bg-primary rounded-pill mb-0 text-center d-flex align-items-center justify-content-center" style="height:30px; width:30px;">{{ $song->execution_count }}</p>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="fas fa-music fa-lg text-muted mb-2"></i>
                            <p class="text-muted mb-0 small">Nenhuma música executada recentemente</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Sidebar - DEVOCIONAIS (segundo destaque) -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-book-open me-2"></i>
                        Últimos Devocionais
                    </h5>
                    <a href="{{ route('devocionais.public.index') }}" class="btn btn-light btn-sm">Ver Todos</a>
                </div>
                <div class="card-body">
                    <div class="row">
                         @forelse($recentDevocionais as $devocional)
                        <div class="col-md-4 p-4 {{
                            $loop->count % 2 == 0
                                ? ($loop->remaining > 1 ? 'border-end' : '')
                                : (!$loop->last ? 'border-end' : '')
                        }}">
                            <h5 class="mb-2">
                                <a href="{{ route('devocionais.public.show', $devocional) }}"
                                   class="text-decoration-none text-dark hover-primary">
                                    {{ Str::limit($devocional->title, 45) }}
                                </a>
                            </h5>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <strong>{{ $devocional->user ? $devocional->user->name : 'Autor desconhecido' }}</strong>
                                </small>
                                <small class="text-muted">
                                    {{ $devocional->devotional_date ? $devocional->devotional_date->format('d/m') : ($devocional->created_at ? $devocional->created_at->format('d/m') : 'S/data') }}
                                </small>
                            </div>


                            @if($devocional->excerpt)
                                <p class="text-muted mb-2 lh-base">{{ Str::limit($devocional->excerpt, 80) }}</p>
                            @endif

                            @if($devocional->bible_references && count($devocional->bible_references) > 0)
                                <div class="mb-2">
                                    @foreach(array_slice($devocional->bible_references, 0, 1) as $reference)
                                        <span class="badge bg-secondary small">
                                            <i class="fas fa-bible me-1"></i>{{ $reference }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="d-flex mt-3">
                                <a href="{{ route('devocionais.public.show', $devocional) }}" class="btn btn-sm btn-primary px-5 text-white">Ler mais</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Nenhum devocional publicado ainda.</p>
                            <a href="{{ route('devocionais.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Criar Devocional
                            </a>
                        </div>
                    @endforelse
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
@endsection
