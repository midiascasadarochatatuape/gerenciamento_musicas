@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Minhas Escalas</h2>
        </div>
    </div>


        <div class="row">
             @forelse($nextSchedules as $schedule)
            <div class="col-md-4">
                <div class="card p-4">
                    <div class="d-flex justify-content-center border-bottom">
                        <h3>{{ $schedule->group->name }}</h3>
                    </div>
                    <div class="d-flex justify-content-between py-3">
                        <div>Data: <strong class="h5 m-0 fw-bolder">{{ $schedule->date->format('d/m/Y') }}</strong></div>
                        <div>Hora: <strong class="h5 m-0 fw-bolder">{{ $schedule->time ? $schedule->formatted_time : '-' }}</strong></div>
                    </div>
                    <div class="d-flex flex-column">
                        <h4>Músicas</h4>
                        <div>
                            <ul class="list-unstyled">
                                @foreach($schedule->songs as $song)
                                    <li>{{ $song->title }} ({{ $song->pivot->tone ?: $song->tone }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="{{ route('schedule.setlist', $schedule) }}" class="btn btn-primary w-100 rounded-pill">Criar Setlist</a>
                    </div>
                </div>
            </div>
            @empty
        <div class="alert alert-info">
            Você não tem escalas programadas.
        </div>
@endforelse
        </div>

</div>
@endsection
