@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 justify-content-end">
        @auth
            @if(auth()->user()->type_user == 'admin' || auth()->user()->type_user == 'tecnico')
            <div class="col-auto">
                <a href="{{ route('songs.edit', $song) }}" class="btn btn-sm px-4 rounded-pill btn-primary d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined text-white m-0" style="font-size: 1rem">settings</span>
                    <span>Editar musica</span>
                </a>
            </div>
            @endif
        @endauth
    </div>


    <div class="row">
        <div class="col-lg-9 col-md-8 col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-4 gap-3">
                        <div class="d-flex justify-content-center align-items-center">
                            @if($song->image)
                                <img src="{{ $song->image }}" class="foto-perfil-show" alt="">
                            @else
                                <img src="{{ asset('assets/img/default-profile.webp') }}" class="foto-perfil-show" alt="">
                            @endif
                        </div>
                        <div>
                            <h2>{{ $song->title }}</h2>
                            <p class="text-muted m-0">Versão: {{ $song->version }}</p>
                        </div>

                    </div>

                    <div class="d-flex align-items-start flex-md-row flex-sm-column flex-column justify-content-between gap-4 mb-4">
                        <div class="d-flex gap-2 flex-column flex-33 flex-shrink-0">
                            <div class="d-flex gap-2 w-100 flex-wrap">
                                @if($song->tone)
                                    <span class="text-white rounded-3 fw-bold bg-blue d-flex align-items-center justify-content-center gap-1 py-03">
                                        <img src="{{ asset('assets/img/tom.svg') }}" height="12" class="text-white" alt="">
                                        {{ $song->tone }}
                                    </span>
                                @endif
                                @if($song->tempo)
                                        <span class="text-white rounded-3 fw-bold bg-green d-flex align-items-center justify-content-center gap-1 py-03">
                                            <img src="{{ asset('assets/img/metronomo.svg') }}" height="12" class="text-white" alt="">
                                            {{ $song->tempo }} - {{ $song->measure }}
                                        </span>
                                @endif
                                @if($song->intensity)
                                    <span class="text-white rounded-3 fw-bold bg-primary d-flex justify-content-center align-items-center gap-1 py-03">
                                        @if ($song->intensity == 'lenta')
                                            <img src="{{ asset('assets/img/lenta.svg') }}" height="12" width="11" class="text-white" alt="">
                                            Lenta
                                        @elseif ($song->intensity == 'media')
                                            <img src="{{ asset('assets/img/media.svg') }}" height="12" width="11" class="text-white" alt="">
                                            Média
                                        @else
                                            <img src="{{ asset('assets/img/rapida.svg') }}" height="12" width="11" class="text-white" alt="">
                                            Rápida
                                        @endif
                                    </span>
                                @endif
                                @if($song->type)
                                    <span class="text-white rounded-3 fw-bold bg-primary d-flex justify-content-center align-items-center gap-1 py-03">
                                        @if ($song->type == 'cantico')
                                            <img src="{{ asset('assets/img/cantico.svg') }}" height="12" class="text-white" alt="">
                                            Cântico
                                        @elseif ($song->type == 'hino')
                                            <img src="{{ asset('assets/img/hino.svg') }}" height="12" class="text-white" alt="">
                                            Hino
                                        @elseif ($song->type == 'corinho')
                                            <img src="{{ asset('assets/img/corinho.svg') }}" height="12" class="text-white" alt="">
                                            Corinho
                                        @else
                                            <img src="{{ asset('assets/img/atual.svg') }}" height="12" class="text-white" alt="">
                                            Atual
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-sm-column flex-column-reverse categorias gap-3">
                            <div class="d-flex flex-lg-row flex-md-column flex-column mt-lg-0 mt-md-3 mt-3 gap-2 align-items-lg-center align-items-md-start align-items-start">
                                @if($song->categories && $song->categories->count() > 0)
                                    <h5 class="mb-0">Categorias: </h5>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @foreach($song->categories as $category)
                                            <div>
                                                <span class="badge bg-secondary">{{ $category->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                @endif
                            </div>
                            <div class="d-flex flex-lg-row flex-md-row flex-column gap-3">
                                @if($song->lyrics)
                                    <button type="button" class="btn btn-songs d-flex gap-lg-2 gap-md-1 gap-1 justify-content-center align-items-center btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#lyricsModal">
                                        <span class="material-symbols-outlined">fullscreen</span>
                                        <span>Abrir Letra</span>
                                    </button>
                                @endif

                                @if($song->chords)
                                    <button type="button" class="btn btn-songs d-flex gap-lg-2 gap-md-1 gap-1 justify-content-center align-items-center btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#chordsModal">
                                        <span class="material-symbols-outlined">fullscreen</span>
                                        <span>Abrir Cifra</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h5>Referências bíblicas:</h5>
                        <div class="d-flex gap-3 flex-wrap">
                            @if($song->bible_reference)
                                <pre class="fst-italic font-sans fs-6 mb-0 referencia">{{ $song->bible_reference }}</pre>
                            @else
                                <p class="text-muted mb-0 referencia">Nenhuma referência bíblica cadastrada.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">informações adicionais</h5>
                    <p>Tocada: <strong>{{ $song->times }}</strong> vez(es)</p>
                    @if($song->link_youtube || $song->link_spotify)
                        <div class="d-flex flex-column gap-2">
                            @if($song->link_youtube)
                                <a href="#" target="_blank" class="btn btn-danger d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#youtubeModal" data-youtube-url="{{ $song->link_youtube }}">
                                    <span class="material-symbols-outlined text-white m-0">smart_display</span>
                                    <span>Veja no YouTube</span>
                                </a>
                            @endif
                            @if($song->link_spotify)
                                <a href="{{ $song->link_spotify }}" target="_blank" class="btn btn-green text-white d-flex align-items-center justify-content-center gap-2">
                                    <span class="material-symbols-outlined text-white m-0">play_circle</span>
                                    <span>Escute no Spotify</span>
                                </a>
                            @endif
                            @if($song->link_drive)
                                <a href="{{ $song->link_drive }}" target="_blank" class="btn btn-blue text-white d-flex align-items-center justify-content-center gap-2">
                                    <span class="material-symbols-outlined text-white">drive_export</span>
                                    <span>Kit de vozes</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal da Letra -->
<div class="modal fade" id="lyricsModal" tabindex="-1" aria-labelledby="lyricsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header pb-0 d-flex flex-column gap-2 align-items-start">
                <h4 class="modal-title h3 fw-bolder mb-0">{{ $song->title }}</h4>
                <p class="d-block text-muted m-0">Versão: {{ $song->version }}</p>
            </div>
            <div class="modal-body" id="lyrics_Editor">
                <pre class="lyrics modal-size-content-l" id="lyricsContent">{{ $song->lyrics }}</pre>
                <form  class="w-100 h-100 d-none" id="lyricsEditor" action="{{ route('songs.update-lyrics', $song->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <textarea class="form-control" name="lyrics" rows="20">{{ $song->lyrics }}</textarea>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center bg-primary">
                <div class="d-flex gap-4 align-items-center">

                    <div class="btn-group">
                        <button
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Velocidade lenta"
                            id="l_scroll-1x" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="slow">
                            <span class="material-symbols-outlined">slow_motion_video</span>
                        </button>
                        <button
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Velocidade média"
                            id="l_scroll-1_5x" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="normal">
                            <span class="material-symbols-outlined">play_arrow</span>
                        </button>
                        <button
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Velocidade rápida"
                            id="l_scroll-2x" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="fast">
                            <span class="material-symbols-outlined">fast_forward</span>
                        </button>
                        <button
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Parar scroll"
                            id="l_scroll-stop" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-stop">
                            <span class="material-symbols-outlined">pause</span>
                        </button>
                        <button
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Ir para o topo"
                            id="l_scroll-top" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-to-top">
                            <span class="material-symbols-outlined">keyboard_arrow_up</span>
                        </button>
                    </div>

                    @auth
                        @if(auth()->user()->type_user == 'admin' || auth()->user()->type_user == 'tecnico')
                        <div class="btn-group">
                            <button
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                data-bs-title="Editar letra"
                                id="edit_lyrics" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white edit-lyrics-btn">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                data-bs-title="Salvar alterações"
                                id="save_lyrics" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white save-lyrics-btn d-none"
                                    onclick="lyricSubmit()">
                                <span class="material-symbols-outlined">save</span>
                            </button>
                        </div>
                        @endif
                    @endauth
                    <button
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="custom-tooltip"
                        data-bs-title="Fechar"
                        type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal da Cifra -->
<div class="modal fade" id="chordsModal" tabindex="-1" aria-labelledby="chordsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header pb-0 d-flex flex-column gap-2 align-items-start">
                <h4 class="modal-title h3 fw-bolder mb-0">{{ $song->title }}</h4>
                <p class="d-block text-muted m-0">Versão: {{ $song->version }}</p>
            </div>
            <div class="modal-body p-0">

                <!-- Cifra -->
                <div id="cifraView" data-original="{!! htmlspecialchars($song->chords) !!}" data-transpose="0"
                    style="display:block;">
                    {!! $song->chords !!}
                </div>

                <!-- Editar -->
                <form id="formEdit" class="w-100 h-100" action="{{ route('songs.update-chords', $song->id) }}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('POST')
                    <textarea id="tiny" class="form-control w-100 h-100" name="html">{!! $song->chords !!}</textarea>
                    <input type="hidden" name="chords" id="htmlInput" />
                    {{-- <button type="submit" class="btn btn-primary" onclick="beforeSubmit()">Salvar</button> --}}
                </form>

            </div>
            <div class="modal-footer d-flex justify-content-md-center justify-content-start bg-primary px-2">
                <div class="d-flex gap-md-4
                    @auth
                        @if(auth()->user()->type_user == 'admin' || auth()->user()->type_user == 'tecnico')
                            gap-0
                        @else
                            gap-2
                        @endif
                    @endauth
                align-items-center">

                    <div class="btn-group">
                        <button id="transpose-down" type="button"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Abaixar meio tom"
                            class="btn btn-sm border-0 d-flex align-items-center btn-outline-white transpose-down">
                            <span class="material-symbols-outlined">remove</span>
                        </button>
                        <button id="transpose-up" type="button"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Aumentar meio tom"
                            class="btn btn-sm border-0 d-flex align-items-center btn-outline-white transpose-up">
                            <span class="material-symbols-outlined">add</span>
                        </button>
                        <button id="reset-chords" type="button"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Voltar ao tom original"
                            class="btn btn-sm border-0 d-flex align-items-center btn-outline-white transpose-up">
                            <span class="material-symbols-outlined">restart_alt</span>
                        </button>
                    </div>

                    <div class="btn-group">
                        <button id="scroll-1x" type="button"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Velocidade lenta"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control"
                            data-speed="slow">
                            <span class="material-symbols-outlined">slow_motion_video</span>
                        </button>
                        <button id="scroll-1_5x" type="button"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Velocidade média"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control"
                            data-speed="normal">
                            <span class="material-symbols-outlined">play_arrow</span>
                        </button>
                        <button id="scroll-2x" type="button"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Velocidade rápida"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control"
                            data-speed="fast">
                            <span class="material-symbols-outlined">fast_forward</span>
                        </button>
                        <button id="scroll-stop" type="button"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Parar scroll"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-stop">
                            <span class="material-symbols-outlined">pause</span>
                        </button>
                        <button id="scroll-top" type="button"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Ir para o topo"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-to-top">
                            <span class="material-symbols-outlined">keyboard_arrow_up</span>
                        </button>
                    </div>

                    @auth

                        @if(auth()->user()->type_user == 'admin' || auth()->user()->type_user == 'tecnico')
                        <div class="btn-group">
                            <button
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                data-bs-title="Editar cifra"
                                id="edit-chords" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white edit-chords-btn">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button id="save-chords" type="button"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                data-bs-title="Salvar alterações"
                                class="btn btn-sm border-0 align-items-center btn-outline-white save-chords-btn" style="display: none"
                                onclick="beforeSubmit()">
                                <span class="material-symbols-outlined">save</span>
                            </button>
                        </div>
                        @endif
                    @endauth
                    <button
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="custom-tooltip"
                        data-bs-title="Fechar"
                        type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal do YouTube -->
<div class="modal fade" id="youtubeModal" tabindex="-1" aria-labelledby="youtubeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="youtubeModalLabel">{{ $song->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="youtubeIframe" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.9.1/tinymce.min.js" integrity="sha512-09JpfVm/UE1F4k8kcVUooRJAxVMSfw/NIslGlWE/FGXb2uRO1Nt4BXAJ3LxPqNbO3Hccdu46qaBPp9wVpWAVhA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

    tinymce.init({
        selector: 'textarea#tiny'
    });

    const notesSharp = ['C', 'C#', 'D', 'Eb', 'E', 'F', 'F#', 'G', 'G#', 'A', 'Bb', 'B'];
    const notesFlat  = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'Bb', 'B'];
    const semitoneMap = {
        'C': 0, 'C#': 1, 'Db': 1,
        'D': 2, 'D#': 3, 'Eb': 3,
        'E': 4, 'D#': 4, 'F': 5,
        'F': 5, 'F#': 6, 'F#': 6,
        'G': 7, 'G#': 8, 'Ab': 8,
        'A': 9, 'Bb': 10, 'Bb': 10,
        'B': 11, 'Bb': 10, 'C': 0
    };

    const wtextarea = document.getElementById('cifraView');
    const ltextarea = document.getElementById('lyrics_Editor');

    function getNoteName(index, preferFlat = false) {
        index = (index + 12) % 12;
        return preferFlat ? notesFlat[index] : notesSharp[index];
    }

    function transposeNoteFull(chordText, semitones) {
        const [main, bass] = chordText.split('/');

        const match = main.match(/^([A-G][b#]?)(.*)$/);
        if (!match) return chordText;
        const [_, note, suffix] = match;

        const index = semitoneMap[note];
        if (index === undefined) return chordText;

        const newNote = getNoteName(index + semitones, false);
        const transposedMain = newNote + suffix;

        if (bass) {
            const bassMatch = bass.match(/^([A-G][b#]?)(.*)$/);
            if (!bassMatch) return transposedMain + '/' + bass;

            const [__, bassNote, bassSuffix] = bassMatch;
            const bassIndex = semitoneMap[bassNote];
            if (bassIndex === undefined) return transposedMain + '/' + bass;

            const newBass = getNoteName(bassIndex + semitones, true);
            return transposedMain + '/' + newBass + bassSuffix;
        }

        return transposedMain;
    }

    // Função principal para transpor as cifras
    let currentTranspose = 0;
    function transposeChords(semitones) {

        currentTranspose += semitones;

        document.querySelectorAll('.cifra-chord').forEach(el => {
            const original = el.getAttribute('data-original');
            const transposed = transposeNoteFull(original, currentTranspose);
            el.innerText = transposed;
        });
    }

    // Scroll automático
    let scrollInterval;

    function startScroll(speed = 1) {
        clearInterval(scrollInterval);

        scrollInterval = setInterval(() => {
            wtextarea.scrollBy(0, 1);
            ltextarea.scrollBy(0, 1);
        }, 60 / speed);

    }



    function stopScroll() {
        clearInterval(scrollInterval);
    }

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', stopScroll);
    });

    function scrollToTop() {
        wtextarea.scrollTo({ top: 0, behavior: 'smooth' });
        ltextarea.scrollTo({ top: 0, behavior: 'smooth' });
        clearInterval(scrollInterval);
    }

    // Para restaurar as cifras originais ao editar
    function resetChords() {
        currentTranspose = 0;
        document.querySelectorAll('.cifra-chord').forEach(el => {
            el.innerText = el.getAttribute('data-original');
        });
    }

    function editChords() {
        // 1. Obter referências aos elementos pelo ID
            const chords = document.getElementById('cifraView');
            const formTy = document.getElementById('formEdit');
            const saveC = document.getElementById('save-chords');

            // 2. Verificar o estado atual e alternar
            if (chords.style.display === 'block') {
                // Se o elemento1 estiver visível, esconda-o e mostre o elemento2
                chords.style.display = 'none';
                formTy.style.display = 'block';
                saveC.style.display = 'flex';
            } else {
                // Se o elemento1 estiver escondido, mostre-o e esconda o elemento2
                chords.style.display = 'block';
                formTy.style.display = 'none';
                saveC.style.display = 'none';
            }

    }

    // Executar após o carregamento da cifra
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.cifra-chord').forEach(el => {
            el.setAttribute('data-original', el.innerText);
        });

        // Botões (ajuste os seletores conforme seu HTML)
        //letra
        document.getElementById('l_scroll-1x').addEventListener('click', () => startScroll(1));
        document.getElementById('l_scroll-1_5x').addEventListener('click', () => startScroll(1.5));
        document.getElementById('l_scroll-2x').addEventListener('click', () => startScroll(2));
        document.getElementById('l_scroll-stop').addEventListener('click', () => stopScroll());
        document.getElementById('l_scroll-top').addEventListener('click', () => scrollToTop());
        //cifra
        document.getElementById('transpose-up').addEventListener('click', () => transposeChords(1));
        document.getElementById('transpose-down').addEventListener('click', () => transposeChords(-1));
        document.getElementById('scroll-1x').addEventListener('click', () => startScroll(1));
        document.getElementById('scroll-1_5x').addEventListener('click', () => startScroll(1.5));
        document.getElementById('scroll-2x').addEventListener('click', () => startScroll(2));
        document.getElementById('scroll-stop').addEventListener('click', () => stopScroll());
        document.getElementById('scroll-top').addEventListener('click', () => scrollToTop());
        document.getElementById('edit-chords').addEventListener('click', () => editChords());
        document.getElementById('reset-chords').addEventListener('click', () => resetChords());

    });

    function beforeSubmit() {
        let content = tinymce.get('tiny').getContent();
        var tempDiv = document.createElement('div');
        const form = document.getElementById('formEdit');

        tempDiv.innerHTML = content;

        // Adiciona classe "cifra-chord" a todo texto em negrito
        tempDiv.querySelectorAll('b, strong').forEach(el => {
            el.classList.add('cifra-chord');
        });

        // Remove a classe "cifra-chord" de elementos que **não estão mais em negrito**
        tempDiv.querySelectorAll('.cifra-chord').forEach(el => {
            const tagName = el.tagName.toLowerCase();
            if (tagName !== 'b' && tagName !== 'strong') {
                el.classList.remove('cifra-chord');
            }
        });

        // Extra: remove classe de elementos que viraram <span> ou outros tags sem bold
        tempDiv.querySelectorAll('*').forEach(el => {
            if (
                el.classList.contains('cifra-chord') &&
                !['B', 'STRONG'].includes(el.tagName) &&
                window.getComputedStyle(el).fontWeight !== 'bold'
            ) {
                el.classList.remove('cifra-chord');
            }
        });

        document.getElementById('htmlInput').value = tempDiv.innerHTML;

        form.submit();

    }

    function lyricSubmit() {
        document.getElementById('lyricsEditor').submit();
    }



    document.addEventListener('DOMContentLoaded', function() {

        function scrollModalToTop(modalBody) {
            modalBody.scrollTop = 0;
        }

        // Configurar os botões scroll-to-top para ambos os modais
        const modals = ['#lyricsModal', '#chordsModal'];

        modals.forEach(modalId => {
            const modal = document.querySelector(modalId);
            const modalBody = modal.querySelector('.modal-body');
            const scrollTopBtn = modal.querySelector('.scroll-to-top');

            if (scrollTopBtn) {
                scrollTopBtn.addEventListener('click', () => {
                    scrollModalToTop(modalBody);
                });
            }
        });


        const editLyricsBtn = document.getElementById('edit_lyrics');
        const saveLyricsBtn = document.getElementById('save_lyrics');


        editLyricsBtn.addEventListener('click', function () {
            clearInterval(scrollInterval);
            lyricsEditor.classList.toggle('d-none');
            lyricsContent.classList.toggle('d-none');
            saveLyricsBtn.classList.toggle('d-none');
        });




        /* if (saveLyricsBtn) {
            saveLyricsBtn.addEventListener('click', function() {
                const songId = window.location.pathname.split('/').pop();
                fetch(`/songs/${songId}/update-lyrics`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        lyrics: lyricsEditor.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        lyricsContent.textContent = lyricsEditor.value;
                        lyricsContent.classList.remove('d-none');
                        lyricsEditor.classList.add('d-none');
                        editLyricsBtn.classList.remove('d-none');
                        saveLyricsBtn.classList.add('d-none');
                        alert('Letra atualizada com sucesso!');
                    }
                })
                .catch(error => {
                    alert('Erro ao salvar a letra: ' + error);
                });
            });
        } */

    });

    // Adicione este código para manipular o modal do YouTube
    document.addEventListener('DOMContentLoaded', function() {
        const youtubeModal = document.getElementById('youtubeModal');
        const youtubeIframe = document.getElementById('youtubeIframe');

        youtubeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const youtubeUrl = button.getAttribute('data-youtube-url');

            // Converte a URL do YouTube para formato de incorporação
            const videoId = getYoutubeVideoId(youtubeUrl);
            const embedUrl = `https://www.youtube.com/embed/${videoId}`;
            youtubeIframe.src = embedUrl;
        });

        youtubeModal.addEventListener('hide.bs.modal', function() {
            youtubeIframe.src = ''; // Limpa o iframe quando o modal é fechado
        });

        // Função para extrair o ID do vídeo da URL do YouTube
        function getYoutubeVideoId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }
    });
</script>

@endsection
