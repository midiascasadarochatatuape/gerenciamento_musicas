@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Grupos</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('group.create') }}" class="btn btn-sm px-4 rounded-pill btn-primary">
                Novo grupo
            </a>
        </div>
    </div>

    <div class="row">
    @foreach($groups as $group)

        <div class="col-md-3 mb-4">
            <div class="card p-4 d-flex flex-column h-100 justify-content-between">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-center border-bottom">
                        <h3>{{ $group->name }}</h3>
                    </div>
                    <div class="d-flex flex-column justify-content-between pt-3">
                        <div class="border-bottom pb-3">
                            <h5 class="m-0"><span class="badge bg-info">{{ $group->users->count() }} membros</span></h5>
                        </div>
                        <div class="d-flex flex-column py-3">
                            <h5>Participantes:</h5>
                            <div>
                                <ul class="list-unstyled">
                                    @forelse($group->users as $user)
                                        <li>{{ $user->name }}</li>
                                    @empty
                                        <li class="text-muted">Nenhum participante</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->type_user == 'admin')
                    <div class="d-flex justify-content-center gap-2">
                        <div>
                            <a href="{{ route('group.edit', $group) }}" class="btn btn-sm px-3 btn-outline-primary">
                                Editar
                            </a>
                        </div>
                        <div>
                            <form action="{{ route('group.destroy', $group) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm px-3 btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este grupo?')">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @endforeach
</div>




    <div class="row">
        <div class="col-12 py-4 d-flex justify-content-center">
            {{ $groups->links('layouts.pagination') }}
        </div>
    </div>
</div>
@endsection
