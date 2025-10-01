@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="fw-bold mb-0 text-center">{{ __('Criar nova conta') }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 py-md-3 py-0 col-form-label text-md-end">{{ __('* Nome') }}</label>

                            <div class="col-md-7">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 py-md-3 py-0 col-form-label text-md-end">{{ __('* E-mail') }}</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="instrument_1" class="col-md-4 py-md-3 py-0 col-form-label text-md-end">{{ __('* Instrumento principal') }}</label>

                            <div class="col-md-7">
                                <select id="instrument_1" class="form-select form-control @error('instrument_1') is-invalid @enderror" name="instrument_1" value="{{ old('instrument_1') }}" required autocomplete="instrument_1" autofocus>
                                    <option value="">Selecione o instrumento principal</option>
                                    <option value="Voz">Vocal</option>
                                    <option value="Guitarra">Guitarra</option>
                                    <option value="Teclado">Teclado</option>
                                    <option value="Violão">Violão</option>
                                    <option value="Bateria">Bateria</option>
                                    <option value="Baixo">Baixo</option>
                                    <option value="Sopro">Sopro</option>
                                    <option value="Cordas">Cordas</option>
                                </select>

                                @error('instrument_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="instrument_2" class="col-md-4 py-md-0 py-0 col-form-label text-md-end">{{ __('Instrumento secundário (se houver)') }}</label>

                            <div class="col-md-7">
                                <select id="instrument_2" class="form-select form-control @error('instrument_2') is-invalid @enderror" name="instrument_2" value="{{ old('instrument_2') }}" autocomplete="instrument_2" autofocus>
                                    <option value="">Selecione o instrumento secundário</option>
                                    <option value="Voz">Vocal</option>
                                    <option value="Guitarra">Guitarra</option>
                                    <option value="Teclado">Teclado</option>
                                    <option value="Violão">Violão</option>
                                    <option value="Bateria">Bateria</option>
                                    <option value="Baixo">Baixo</option>
                                    <option value="Sopro">Sopro</option>
                                    <option value="Cordas">Cordas</option>
                                </select>

                                @error('instrument_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 py-md-3 py-0 col-form-label text-md-end">{{ __('* Senha') }}</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 py-md-3 py-0 col-form-label text-md-end">{{ __('* Confirmar Senha') }}</label>

                            <div class="col-md-7">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <p class="m-0 fst-italic text-secondary">* Campos são obrigatórios</p>
                        </div>

                        <div class="row mb-0">
                            <div class="col-12 text-center mb-3">
                                <button type="submit" class="btn btn-primary px-5">
                                    {{ __('Finalizar cadastro') }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <a href="{{ route('login') }}" class="btn btn-sm btn-link">Já tenho uma conta</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
