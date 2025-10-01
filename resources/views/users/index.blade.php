@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Usuários</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('user.create') }}" class="btn btn-sm px-4 rounded-pill btn-primary">
                Novo usuário
            </a>
        </div>
    </div>

    @if ( session()->has('success'))
        <div class="alert alert-success bg-success text-white alert-dismissible fade show" role="alert">
            <strong>Sucesso!</strong>  {{ session()->get('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ( session()->has('error'))
        <div class="alert alert-danger bg-danger text-white alert-dismissible fade show" role="alert">
            <strong>Erro!</strong>  {{ session()->get('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm bg-transparent">
                    <thead>
                        <tr>
                            <th class="fw-bold bg-primary text-white py-2 align-middle">Nome</th>
                            <th class="fw-bold bg-primary text-white py-2 align-middle">E-mail</th>
                            <th class="fw-bold bg-primary text-white py-2 align-middle">Tipo</th>
                            <th class="fw-bold bg-primary text-white py-2 align-middle text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="bg-transparent py-1 align-middle w-50">{{ $user->name }}</td>
                            <td class="bg-transparent py-1 align-middle ">{{ $user->email }}</td>
                            <td class="bg-transparent py-1 align-middle ">{{ ucfirst($user->type_user) }}</td>
                            <td class="bg-transparent py-1 align-middle ">
                                <div class="d-flex gap-3 justify-content-center align-items-center">
                                    <a href="{{ route('user.edit', $user) }}" class="btn btn-sm py-0 px-4 btn-outline-primary">
                                        Editar
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm px-4 py-0 btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            Excluir
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links('layouts.pagination') }}
        </div>
    </div>
</div>
@endsection
