@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <div class="col">
            <h2>Escalas</h2>
        </div>
        <div class="col d-flex justify-content-end gap-4">
            <div class="text-end">
                <a href="{{ route('schedule.user') }}" class="btn btn-sm px-4 rounded-pill btn-primary">Minha Escala</a>
            </div>
            @if(auth()->user()->type_user == 'admin')
                <div class="text-end">
                    <a href="{{ route('schedule.create') }}" class="btn btn-sm px-4 rounded-pill btn-primary">Nova Escala</a>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        @forelse($schedules as $schedule)

            <div class="col-md-3 mb-4">
                <div class="card p-4 d-flex flex-column h-100 justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-center border-bottom">
                            <h3>{{ $schedule->group->name }}</h3>
                        </div>
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <div>Data: <strong class="h5 m-0 fw-bolder">{{ $schedule->date->format('d/m') }}</strong></div>
                            <div>Hora: <strong class="h5 m-0 fw-bolder">{{ $schedule->time ? $schedule->formatted_time : '-' }}</strong></div>
                        </div>
                        <div class="d-flex flex-column py-3">
                            <h5>Musicas</h5>
                            <div>
                                <ul class="list-unstyled">
                                    @foreach($schedule->songs as $song)
                                        <li><a class="text-muted" href="{{ route('songs.show', $song->id) }}">{{ $song->title }} ({{ $song->pivot->tone ?: $song->tone }})</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @if ($schedule->event_type)
                            <div class="d-flex">
                                <p>Evento: <strong>{{ $schedule->event_type ?: '-' }}</strong></p>
                            </div>
                        @endif
                    </div>


                    @php
                        $userGroupIds = auth()->user()->groups->pluck('id')->toArray();
                    @endphp

                    @if(auth()->user()->type_user == 'admin' || (auth()->user()->type_user == 'tecnico' && in_array($schedule->group_id, $userGroupIds)))
                        <div class="d-flex justify-content-center gap-3 border-top pt-3">
                            <div>
                                <a href="{{ route('schedule.edit', $schedule) }}" class="btn btn-sm px-4 btn-primary">Editar</a>
                            </div>
                            <div>
                                <form action="{{ route('schedule.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta escala?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm px-4 btn-danger">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            @empty
        @endforelse
    </div>

</div>
@endsection
