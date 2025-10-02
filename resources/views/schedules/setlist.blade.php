@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Setlist - {{ $schedule->group->name }}</h2>
            <p>Data: {{ $schedule->date->format('d/m/Y') }} - Hora: {{ $schedule->time ? $schedule->formatted_time : '-' }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 order-md-0 order-1 mb-md-2 mb-5">
            <div class="songs-container" id="sortable">
                @foreach($schedule->songs as $song)
                    <div class="card mb-3" data-song-id="{{ $song->id }}">
                        <div class="card-body px-3 d-flex justify-content-between">
                            <div class="d-flex justify-content-start align-items-center pe-3">
                                <span class="material-symbols-outlined me-3 handle" style="cursor: move">drag_indicator</span>
                                <p class="mb-0 text-primary fw-bold lh-sm">{{ $song->title }} ({{ $song->pivot->tone ?: $song->tone }})</p>
                            </div>
                            <div class="d-flex flex-wrap flex-column gap-1 align-items-center justify-content-center">
                                @if($song->lyrics)
                                    <div>
                                        <button type="button" class="btn btn-primary px-3 py-0 btn-sm open-lyrics-modal"
                                                data-bs-toggle="modal" data-bs-target="#lyricsModal{{$song->id}}"
                                                style="font-size: .8rem; text-wrap: nowrap;"
                                                data-song-id="{{ $song->id }}">
                                            Ver Letra
                                        </button>
                                    </div>
                                @endif
                                @if($song->chords)
                                    <div>
                                        <button type="button" class="btn btn-secondary px-3 py-0 btn-sm text-white open-chords-modal"
                                                data-bs-toggle="modal" data-bs-target="#chordsModal{{$song->id}}"
                                                style="font-size: .8rem; text-wrap: nowrap;"
                                                data-song-id="{{ $song->id }}">
                                            Ver Cifra
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Modal da Letra -->
                    <div class="modal fade" id="lyricsModal{{$song->id}}" tabindex="-1" aria-labelledby="lyricsModalLabel">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header py-2 d-flex justify-content-between">
                                    <div class="d-flex flex-column gap-1 align-items-start">
                                        <h4 class="modal-title h4 fw-bolder mb-0">{{ $song->title }}</h4>
                                        <p class="d-block text-muted m-0 small">Versão: {{ $song->version }}</p>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">

                                        <button onclick="alternarContraste()" class="btn btn-sm border-0 p-0" id="contrast"><span class="material-symbols-outlined cursor-pointer">contrast</span></button>

                                        <div class="divider"></div>
                                        <button onclick="diminuirFonte()" class="btn btn-sm border-0 p-0" id="text_decrease"><span class="material-symbols-outlined cursor-pointer">text_decrease</span></button>
                                        <button onclick="aumentarFonte()" class="btn btn-sm border-0 p-0" id="text_increase"><span class="material-symbols-outlined cursor-pointer">text_increase</span></button>


                                    </div>
                                </div>
                                <div class="modal-body" id="lyrics_Editor_{{$song->id}}">
                                    <pre class="lyrics modal-size-content-l" id="lyricsContent_{{$song->id}}">{{ $song->lyrics }}</pre>
                                </div>
                                <div class="modal-footer d-flex justify-content-center bg-primary">
                                    <div class="d-flex gap-4 align-items-center">
                                        <!-- Botão de navegação para esquerda -->
                                        <button
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Voltar à música anterior"
                                            id="prev-song-lyrics_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white prev-song" data-song-id="{{$song->id}}" data-modal-type="lyrics">
                                            <span class="material-symbols-outlined">arrow_back</span>
                                        </button>

                                        <div class="btn-group">
                                            <button
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Velocidade lenta"
                                                id="l_scroll-1x_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="slow">
                                                <span class="material-symbols-outlined">slow_motion_video</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Velocidade média"
                                                id="l_scroll-1_5x_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="normal">
                                                <span class="material-symbols-outlined">play_arrow</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Velocidade rápida"
                                                id="l_scroll-2x_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="fast">
                                                <span class="material-symbols-outlined">fast_forward</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Parar scroll"
                                                id="l_scroll-stop_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-stop">
                                                <span class="material-symbols-outlined">pause</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Ir para o topo"
                                                id="l_scroll-top_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white scroll-to-top">
                                                <span class="material-symbols-outlined">keyboard_arrow_up</span>
                                            </button>
                                        </div>

                                        @auth
                                            @if(auth()->user()->type_user == 'admin' || auth()->user()->type_user == 'tecnico')
                                            <div class="btn-group d-none">
                                                <button id="edit_lyrics_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white edit-lyrics-btn">
                                                    <span class="material-symbols-outlined">edit</span>
                                                </button>
                                                <button id="save_lyrics_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white save-lyrics-btn d-none"
                                                        onclick="lyricSubmit({{$song->id}})">
                                                    <span class="material-symbols-outlined">save</span>
                                                </button>
                                            </div>
                                            @endif
                                        @endauth
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                                        <!-- Botão de navegação para direita -->
                                        <button
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Ir à próxima música"
                                            id="next-song-lyrics_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white next-song" data-song-id="{{$song->id}}" data-modal-type="lyrics">
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal da Cifra -->
                    <div class="modal fade" id="chordsModal{{$song->id}}" tabindex="-1" aria-labelledby="chordsModalLabel">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header py-2 d-flex justify-content-between">
                                    <div class="d-flex flex-column gap-1 align-items-start">
                                        <h4 class="modal-title h4 fw-bolder mb-0">{{ $song->title }}</h4>
                                        <p class="d-block text-muted m-0 small">Versão: {{ $song->version }}</p>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">

                                        <button onclick="alternarContraste()" class="btn btn-sm border-0 p-0" id="contrast"><span class="material-symbols-outlined cursor-pointer">contrast</span></button>

                                        <div class="divider"></div>
                                        <button onclick="diminuirFontePT()" class="btn btn-sm border-0 p-0" id="text_decrease"><span class="material-symbols-outlined cursor-pointer">text_decrease</span></button>
                                        <button onclick="aumentarFontePT()" class="btn btn-sm border-0 p-0" id="text_increase"><span class="material-symbols-outlined cursor-pointer">text_increase</span></button>


                                    </div>
                                </div>
                                <div class="modal-body p-4 bg-white" id="chords_Editor_{{$song->id}}">

                                    <!-- Cifra -->
                                    <div id="cifraView_{{$song->id}}" class="cifraView" data-original="{!! htmlspecialchars($song->chords) !!}" data-transpose="0"
                                        style="display:block;">
                                        {!! $song->chords !!}
                                    </div>

                                </div>
                                <div class="modal-footer d-flex justify-content-center bg-primary">
                                    <div class="d-flex gap-md-4 gap-2 align-items-center">
                                        <!-- Botão de navegação para esquerda -->
                                        <button
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Voltar à música anterior"
                                            id="prev-song-chords_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white prev-song" data-song-id="{{$song->id}}" data-modal-type="chords">
                                            <span class="material-symbols-outlined">arrow_back</span>
                                        </button>

                                        <div class="btn-group">
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Diminuir meio tom"
                                                id="transpose-down_{{$song->id}}" type="button" class="btn btn-sm border-0 px-1 d-flex align-items-center btn-outline-white transpose-down" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">remove</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Aumentar meio tom"
                                                id="transpose-up_{{$song->id}}" type="button" class="btn btn-sm border-0 px-1 d-flex align-items-center btn-outline-white transpose-up" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">add</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Voltar à cifra original"
                                                id="reset-chords_{{$song->id}}" type="button" class="btn btn-sm border-0 px-1 d-flex align-items-center btn-outline-white reset-chords" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">restart_alt</span>
                                            </button>
                                        </div>

                                        <div class="btn-group">
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Velocidade lenta"
                                                id="scroll-1x_{{$song->id}}" type="button" class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="slow" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">slow_motion_video</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Velocidade média"
                                                id="scroll-1_5x_{{$song->id}}" type="button" class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="normal" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">play_arrow</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Velocidade rápida"
                                                id="scroll-2x_{{$song->id}}" type="button" class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control" data-speed="fast" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">fast_forward</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Parar scroll"
                                                id="scroll-stop_{{$song->id}}" type="button" class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-stop" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">pause</span>
                                            </button>
                                            <button
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-custom-class="custom-tooltip"
                                                data-bs-title="Ir ao topo"
                                                id="scroll-top_{{$song->id}}" type="button" class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-to-top" data-song-id="{{$song->id}}">
                                                <span class="material-symbols-outlined">keyboard_arrow_up</span>
                                            </button>
                                        </div>

                                        @auth
                                            @if(auth()->user()->type_user == 'admin' || auth()->user()->type_user == 'tecnico')
                                            <div class="btn-group d-none">
                                                <button
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Editar cifra"
                                                    id="edit-chords_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white edit-chords-btn" data-song-id="{{$song->id}}">
                                                    <span class="material-symbols-outlined">edit</span>
                                                </button>
                                                <button
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Salvar alterações"
                                                    id="save-chords_{{$song->id}}" type="button" class="btn btn-sm border-0 align-items-center btn-outline-white save-chords-btn" style="display: none" data-song-id="{{$song->id}}" onclick="beforeSubmit({{$song->id}})">
                                                    <span class="material-symbols-outlined">save</span>
                                                </button>
                                            </div>
                                            @endif
                                        @endauth

                                        <button
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Fechar"
                                            type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                                        <button
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-custom-class="custom-tooltip"
                                            data-bs-title="Ir à próxima música"
                                            id="next-song-chords_{{$song->id}}" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white next-song" data-song-id="{{$song->id}}" data-modal-type="chords">
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-12 my-4 order-md-1 order-0">
            <div class="d-flex justify-content-center gap-md-4 gap-2">
                <button id="start-lyrics-btn" class="btn px-md-4 px-3 btn-primary rounded-pill d-flex align-items-center gap-md-2 gap-1"><span class="material-symbols-outlined">play_circle</span> Começar Letra</button>
                <button id="start-chords-btn" class="btn px-md-4 px-3 btn-secondary text-white rounded-pill d-flex align-items-center gap-md-2 gap-1"><span class="material-symbols-outlined">play_circle</span> Começar Cifra</button>
            </div>
        </div>
    </div>
</div>


@endsection


<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>

    const FONT_STEP         = 0.1;
    const MIN_FONT_SIZE     = 1.0;
    const MAX_FONT_SIZE     = 2.4;
    const STORAGE_KEY       = 'modalFontSize';
    const DEFAULT_FONT_SIZE = 1.4;

    function getFontSize() {
        const saved = localStorage.getItem(STORAGE_KEY);
        return saved ? parseFloat(saved) : DEFAULT_FONT_SIZE;
    }

    function setFontSize(size) {
        localStorage.setItem(STORAGE_KEY, size.toFixed(1));
    }

    function aplicarFonteNosModais() {
        const modais = document.querySelectorAll('.modal-size-content-l');
        const tamanho = getFontSize();

        modais.forEach(modal => {
            modal.style.fontSize = `${tamanho}rem`;
        });
    }


    function ajustarFonte(delta) {
        let tamanhoAtual = getFontSize();
        let novoTamanho = tamanhoAtual + delta;

        if (novoTamanho >= MIN_FONT_SIZE && novoTamanho <= MAX_FONT_SIZE) {
            setFontSize(novoTamanho);
            aplicarFonteNosModais();
        }
    }

    function aumentarFonte() {
        ajustarFonte(FONT_STEP);
    }

    function diminuirFonte() {
        ajustarFonte(-FONT_STEP);
    }

    // Aplica o tamanho salvo ao abrir qualquer modal
    document.addEventListener('shown.bs.modal', () => {
        aplicarFonteNosModais();
    });

    const PT_STEP = 0.5;
const PT_MIN = 10;
const PT_MAX = 20;
const PT_STORAGE_KEY = 'modalFontSizePT';
const PT_DEFAULT = 14;

function getFontSizePT() {
    const saved = localStorage.getItem(PT_STORAGE_KEY);
    return saved ? parseFloat(saved) : PT_DEFAULT;
}

function setFontSizePT(size) {
    localStorage.setItem(PT_STORAGE_KEY, size.toFixed(1));
}

function aplicarFontePT() {
    const modais = document.querySelectorAll('.cifraView > pre');
    const tamanho = getFontSizePT();
    modais.forEach(modal => {
        modal.style.fontSize = `${tamanho}pt`;
    });
}

function ajustarFontePT(delta) {
    let tamanhoAtual = getFontSizePT();
    let novoTamanho = tamanhoAtual + delta;

    if (novoTamanho >= PT_MIN && novoTamanho <= PT_MAX) {
        setFontSizePT(novoTamanho);
        aplicarFontePT();
    }
}

function aumentarFontePT() {
    ajustarFontePT(PT_STEP);
}

function diminuirFontePT() {
    ajustarFontePT(-PT_STEP);
}

document.addEventListener('shown.bs.modal', aplicarFontePT);




    const songs = @json($schedule->songs);
    let currentSongIndex = 0;

    function alternarContraste() {
        const modoAtual = localStorage.getItem('modoContraste') || 'normal';
        const novoModo = modoAtual === 'normal' ? 'escuro' : 'normal';
        localStorage.setItem('modoContraste', novoModo);

        aplicarContrasteNosModais();
    }

    function aplicarContrasteNosModais() {
        const todosOsModais = document.querySelectorAll('.modal');
        const modo = localStorage.getItem('modoContraste') || 'normal';

        todosOsModais.forEach(modal => {
            if (modo === 'escuro') {
            modal.classList.add('contraste-escuro');
            } else {
            modal.classList.remove('contraste-escuro');
            }
        });
    }

document.addEventListener('DOMContentLoaded', function() {

    const notesSharp = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'Bb', 'B'];
    const notesFlat  = ['C', 'Db', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'Bb', 'B'];
    const semitoneMap = {
        'C': 0, 'C#': 1, 'Db': 1,
        'D': 2, 'D#': 3, 'Eb': 3,
        'E': 4, 'D#': 4, 'F': 5,
        'F': 5, 'F#': 6, 'F#': 6,
        'G': 7, 'G#': 8, 'Ab': 8,
        'A': 9, 'A#': 10, 'Bb': 10,
        'B': 11, 'Bb': 11, 'C': 0
    };

    // Mapa para armazenar os intervalos de scroll para cada música
    const scrollIntervals = {};
    // Mapa para armazenar o estado de transposição para cada música
    const songTransposeState = {};

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
    function transposeChords(songId, semitones) {
        if (!songTransposeState[songId]) {
            songTransposeState[songId] = 0;
        }

        songTransposeState[songId] += semitones;

        document.querySelectorAll(`#cifraView_${songId} .cifra-chord`).forEach(el => {
            const original = el.getAttribute('data-original');
            const transposed = transposeNoteFull(original, songTransposeState[songId]);
            el.innerText = transposed;
        });
    }

    // FUNÇÕES DE SCROLL CORRIGIDAS
    function startScroll(songId, speed = 1) {
        //console.log(`Iniciando scroll para songId: ${songId}, speed: ${speed}`);

        // Primeiro, parar qualquer scroll existente
        stopScroll(songId);

        const cifraView = document.getElementById(`chords_Editor_${songId}`);
        const lyricsContent = document.getElementById(`lyrics_Editor_${songId}`);

        //console.log(`Elementos encontrados - cifraView: ${!!cifraView}, lyricsContent: ${!!lyricsContent}`);

        // Verificar se pelo menos um elemento existe
        if (!cifraView && !lyricsContent) {
            console.error(`Nenhum elemento de conteúdo encontrado para songId: ${songId}`);
            return;
        }

        // Calcular intervalo baseado na velocidade (quanto menor o número, mais rápido)
        const intervalTime = Math.max(10, Math.round(80 / speed));

        scrollIntervals[songId] = setInterval(() => {
            try {
                if (cifraView && !cifraView.classList.contains('d-none')) {
                    cifraView.scrollBy(0, 1);
                }
                if (lyricsContent && !lyricsContent.classList.contains('d-none')) {
                    lyricsContent.scrollBy(0, 1);
                }
            } catch (error) {
                console.error(`Erro durante o scroll para songId ${songId}:`, error);
                stopScroll(songId);
            }
        }, intervalTime);

        //console.log(`Scroll iniciado com intervalo: ${intervalTime}ms`);
    }

    function scrollToTop(songId) {
        //console.log(`Rolando para o topo - songId: ${songId}`);

        // Parar o scroll automático primeiro
        stopScroll(songId);

        const cifraView = document.getElementById(`chords_Editor_${songId}`);
        const lyricsContent = document.getElementById(`lyrics_Editor_${songId}`);

        console.log(`Elementos encontrados - cifraView: ${!!cifraView}, lyricsContent: ${!!lyricsContent}`);

        if (cifraView) {
            cifraView.scrollTo({ top: 0, behavior: 'smooth' });
            console.log('ScrollTo executado para cifraView');
        }

        if (lyricsContent) {
            lyricsContent.scrollTo({ top: 0, behavior: 'smooth' });
            console.log('ScrollTo executado para lyricsContent');
        }
    }

    function stopScroll(songId) {
        console.log(`Parando scroll para songId: ${songId}`);

        if (scrollIntervals[songId]) {
            clearInterval(scrollIntervals[songId]);
            delete scrollIntervals[songId];
            console.log(`Scroll interrompido para songId: ${songId}`);
        } else {
            console.log(`Nenhum scroll ativo encontrado para songId: ${songId}`);
        }
    }

    // Parar todos os scrolls quando qualquer modal é fechado
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const songId = this.id.replace('chordsModal', '').replace('lyricsModal', '');
            console.log(`Modal fechado, parando scroll para songId: ${songId}`);
            stopScroll(songId);
        });
    });

    // Para restaurar as cifras originais ao editar
    function resetChords(songId) {
        songTransposeState[songId] = 0;
        document.querySelectorAll(`#cifraView_${songId} .cifra-chord`).forEach(el => {
            el.innerText = el.getAttribute('data-original');
        });
    }

    function editChords(songId) {
        const chords = document.getElementById(`cifraView_${songId}`);
        const formTy = document.getElementById(`formEdit_${songId}`);
        const saveC = document.getElementById(`save-chords_${songId}`);

        if (chords.style.display === 'block') {
            chords.style.display = 'none';
            formTy.style.display = 'block';
            saveC.style.display = 'flex';
        } else {
            chords.style.display = 'block';
            formTy.style.display = 'none';
            saveC.style.display = 'none';
        }
    }

    // INICIALIZAÇÃO CORRIGIDA DOS BOTÕES
    songs.forEach(song => {
        const songId = song.id;
        console.log(`Inicializando controles para songId: ${songId}`);

        // Inicializar os atributos data-original para as cifras
        document.querySelectorAll(`#cifraView_${songId} .cifra-chord`).forEach(el => {
            el.setAttribute('data-original', el.innerText);
        });

        // Configurar os botões de controle para cada música
        // Botões de letra - VERSÃO CORRIGIDA
        const lScrollButtons = [
            { id: `l_scroll-1x_${songId}`, action: () => startScroll(songId, 1) },
            { id: `l_scroll-1_5x_${songId}`, action: () => startScroll(songId, 1.5) },
            { id: `l_scroll-2x_${songId}`, action: () => startScroll(songId, 2) },
            { id: `l_scroll-stop_${songId}`, action: () => stopScroll(songId) },
            { id: `l_scroll-top_${songId}`, action: () => scrollToTop(songId) },
        ];

        lScrollButtons.forEach(button => {
            const el = document.getElementById(button.id);
            if (el) {
                console.log(`Botão encontrado e configurado: ${button.id}`);
                // Remover event listeners existentes (se houver)
                el.removeEventListener('click', button.action);
                // Adicionar o novo event listener
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log(`Botão clicado: ${button.id}`);
                    button.action();
                });
            } else {
                console.warn(`Botão não encontrado: ${button.id}`);
            }
        });

        // Botões de cifra - VERSÃO CORRIGIDA
        const cButtons = [
            { id: `transpose-up_${songId}`, action: () => transposeChords(songId, 1) },
            { id: `transpose-down_${songId}`, action: () => transposeChords(songId, -1) },
            { id: `reset-chords_${songId}`, action: () => resetChords(songId) },
            { id: `scroll-1x_${songId}`, action: () => startScroll(songId, 1) },
            { id: `scroll-1_5x_${songId}`, action: () => startScroll(songId, 1.5) },
            { id: `scroll-2x_${songId}`, action: () => startScroll(songId, 2) },
            { id: `scroll-stop_${songId}`, action: () => stopScroll(songId) },
            { id: `scroll-top_${songId}`, action: () => scrollToTop(songId) },
            { id: `edit-chords_${songId}`, action: () => editChords(songId) },
        ];

        cButtons.forEach(button => {
            const el = document.getElementById(button.id);
            if (el) {
                console.log(`Botão encontrado e configurado: ${button.id}`);
                // Remover event listeners existentes (se houver)
                el.removeEventListener('click', button.action);
                // Adicionar o novo event listener
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log(`Botão clicado: ${button.id}`);
                    button.action();
                });
            } else {
                console.warn(`Botão não encontrado: ${button.id}`);
            }
        });

        // Configurar os botões de edição de letras
        const editLyricsBtn = document.getElementById(`edit_lyrics_${songId}`);
        const saveLyricsBtn = document.getElementById(`save_lyrics_${songId}`);
        const lyricsContent = document.getElementById(`lyricsContent_${songId}`);
        const lyricsEditor = document.getElementById(`lyricsEditor_${songId}`);

        if (editLyricsBtn && lyricsContent && lyricsEditor) {
            editLyricsBtn.addEventListener('click', function () {
                stopScroll(songId);
                lyricsEditor.classList.toggle('d-none');
                lyricsContent.classList.toggle('d-none');
                saveLyricsBtn.classList.toggle('d-none');
            });
        }
    });

    // Resto do código permanece igual...

    // Função para submeter o formulário de cifras
    window.beforeSubmit = function(songId) {
        let content = tinymce.get(`tiny_${songId}`).getContent();
        var tempDiv = document.createElement('div');
        const form = document.getElementById(`formEdit_${songId}`);

        tempDiv.innerHTML = content;

        // Adiciona classe "cifra-chord" a todo texto em negrito
        tempDiv.querySelectorAll('b, strong').forEach(el => {
            el.classList.add('cifra-chord');
        });

        // Remove a classe "cifra-chord" de elementos que não estão mais em negrito
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

        document.getElementById(`htmlInput_${songId}`).value = tempDiv.innerHTML;
        form.submit();
    };

    // Função para submeter o formulário de letras
    window.lyricSubmit = function(songId) {
        document.getElementById(`lyricsEditor_${songId}`).submit();
    };

    // Função para obter a ordem atual das músicas
    function getCurrentSongsOrder() {
        return Array.from(document.querySelectorAll('#sortable .card')).map(el => el.dataset.songId);
    }

    function getCurrentSongsOrder() {
        return Array.from(document.querySelectorAll('#sortable .card')).map(el => el.dataset.songId);
    }

    new Sortable(document.getElementById('sortable'), {
        animation: 150,
        handle: '.handle',
        onEnd: function() {
            console.log('Nova ordem após sortable:', getCurrentSongsOrder());
            // Atualizar os botões de navegação para todos os modais abertos
            updateAllNavigationButtons();
        }
    });

    // Função para atualizar TODOS os botões de navegação (útil após reordenação)
    function updateAllNavigationButtons() {
        // Encontrar todos os modais atualmente abertos
        const openModals = document.querySelectorAll('.modal.show');

        openModals.forEach(modal => {
            const modalId = modal.id;
            let songId, modalType;

            // Determinar o tipo de modal e songId
            if (modalId.includes('chordsModal')) {
                songId = modalId.replace('chordsModal', '');
                modalType = 'chords';
            } else if (modalId.includes('lyricsModal')) {
                songId = modalId.replace('lyricsModal', '');
                modalType = 'lyrics';
            }

            if (songId && modalType) {
                console.log(`Atualizando navegação para modal aberto: ${modalType}, songId: ${songId}`);
                updateNavigationButtons(songId, modalType);
            }
        });
    }

    // Função para atualizar o estado dos botões de navegação
    function updateNavigationButtons(songId, modalType) {
        const songsOrder = getCurrentSongsOrder().map(String);
        const currentIndex = songsOrder.indexOf(String(songId));

        console.log(`Atualizando navegação - songId: ${songId}, modalType: ${modalType}, currentIndex: ${currentIndex}, ordem: [${songsOrder.join(', ')}]`);

        const prevButton = document.getElementById(`prev-song-${modalType}_${songId}`);
        const nextButton = document.getElementById(`next-song-${modalType}_${songId}`);

        if (prevButton) {
            if (currentIndex <= 0) {
                prevButton.classList.add('disabled');
                prevButton.setAttribute('disabled', 'disabled');
                console.log(`Botão anterior desabilitado para ${modalType}_${songId}`);
            } else {
                prevButton.classList.remove('disabled');
                prevButton.removeAttribute('disabled');
                console.log(`Botão anterior habilitado para ${modalType}_${songId}`);
            }
        }

        if (nextButton) {
            if (currentIndex === -1 || currentIndex >= songsOrder.length - 1) {
                nextButton.classList.add('disabled');
                nextButton.setAttribute('disabled', 'disabled');
                console.log(`Botão próximo desabilitado para ${modalType}_${songId}`);
            } else {
                nextButton.classList.remove('disabled');
                nextButton.removeAttribute('disabled');
                console.log(`Botão próximo habilitado para ${modalType}_${songId}`);
            }
        }
    }

    // Modificar a função navigateToSong para usar a ordem atual sempre
    function navigateToSong(currentSongId, direction, modalType) {
        console.log(`Navegando: songId: ${currentSongId}, direção: ${direction}, tipo: ${modalType}`);

        // Parar qualquer scroll automático
        stopScroll(currentSongId);

        // SEMPRE obter a ordem atual das músicas (atualizada após sortable)
        const songsOrder = getCurrentSongsOrder();
        console.log(`Ordem atual dos cards: [${songsOrder.join(', ')}]`);

        // Encontrar o índice da música atual na ordem ATUAL
        const currentIndex = songsOrder.indexOf(currentSongId.toString());
        console.log(`Índice atual: ${currentIndex}`);

        // Verificar se está no limite e não permitir navegação
        if (direction === 'prev' && currentIndex <= 0) {
            console.log('Não pode navegar para anterior - já é o primeiro');
            return;
        }
        if (direction === 'next' && currentIndex >= songsOrder.length - 1) {
            console.log('Não pode navegar para próximo - já é o último');
            return;
        }

        // Calcular o próximo índice
        let nextIndex;
        if (direction === 'next') {
            nextIndex = currentIndex + 1;
        } else {
            nextIndex = currentIndex - 1;
        }

        // Obter o ID da próxima música baseado na ordem ATUAL
        const nextSongId = songsOrder[nextIndex];
        console.log(`Próxima música: ${nextSongId} (índice: ${nextIndex})`);

        // Fechar o modal atual
        const currentModal = document.getElementById(`${modalType}Modal${currentSongId}`);
        const bsCurrentModal = bootstrap.Modal.getInstance(currentModal);
        if (bsCurrentModal) {
            bsCurrentModal.hide();
        }

        // Abrir o modal da próxima música após um pequeno delay
        setTimeout(() => {
            const nextModal = document.getElementById(`${modalType}Modal${nextSongId}`);
            if (nextModal) {
                const bsNextModal = new bootstrap.Modal(nextModal);
                bsNextModal.show();

                // Atualizar os botões de navegação para o novo modal
                setTimeout(() => {
                    updateNavigationButtons(nextSongId, modalType);
                }, 200);
            }
        }, 150);
    }

    // Adicionar event listeners para os botões de navegação
    document.querySelectorAll('.prev-song, .next-song').forEach(button => {
        button.addEventListener('click', function() {
            const songId = this.getAttribute('data-song-id');
            const modalType = this.getAttribute('data-modal-type');
            const direction = this.classList.contains('next-song') ? 'next' : 'prev';
            navigateToSong(songId, direction, modalType);
        });
    });

    // Inicializar os botões de navegação quando os modais são abertos
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const songId = this.getAttribute('data-song-id');
            const modalType = target.includes('lyricsModal') ? 'lyrics' : 'chords';

            // Atualizar os botões após um pequeno delay para garantir que o modal esteja aberto
            setTimeout(() => {
                updateNavigationButtons(songId, modalType);
            }, 200);
        });
    });

    // Função para abrir o primeiro modal da lista (sempre baseado na ordem atual)
    function openFirstModal(modalType) {
        const songsOrder = getCurrentSongsOrder();
        console.log(`Abrindo primeiro modal ${modalType}, ordem atual: [${songsOrder.join(', ')}]`);

        if (songsOrder.length > 0) {
            const firstSongId = songsOrder[0];
            const modalId = `${modalType}Modal${firstSongId}`;
            const modal = document.getElementById(modalId);

            console.log(`Tentando abrir modal: ${modalId}`);

            if (modal) {
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Atualizar os botões de navegação
                setTimeout(() => {
                    updateNavigationButtons(firstSongId, modalType);
                }, 200);
            } else {
                console.error(`Modal não encontrado: ${modalId}`);
            }
        }
    }

    // Event listeners para os botões "Começar Letra" e "Começar Cifra"
    const startLyricsBtn = document.getElementById('start-lyrics-btn');
    if (startLyricsBtn) {
        startLyricsBtn.addEventListener('click', function() {
            openFirstModal('lyrics');
        });
    }

    const startChordsBtn = document.getElementById('start-chords-btn');
    if (startChordsBtn) {
        startChordsBtn.addEventListener('click', function() {
            openFirstModal('chords');
        });
    }
});
</script>
