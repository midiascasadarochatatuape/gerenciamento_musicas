@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Exemplo de botão -->
<button data-bs-toggle="modal" class="btn btn-primary" data-bs-target="#modalCifra{{ $cifra->id }}">Ver</button>

<!-- Modal -->
<div class="modal fade" id="modalCifra{{ $cifra->id }}" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="ps-3">{{ $cifra->titulo }}</h4>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Cifra -->
                <div id="cifraView" data-original="{!! htmlspecialchars($cifra->html) !!}" data-transpose="0"
                    style="display:block;">
                    {!! $cifra->html !!}
                </div>

                <!-- Editar -->
                <form id="formEdit" class="w-100 h-100" action="{{ route('cifras.update', $cifra->id) }}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('PUT')
                    <textarea id="tiny" class="form-control w-100 h-100" name="html">{!! $cifra->html !!}</textarea>
                    <input type="hidden" name="html" id="htmlInput" />
                    {{-- <button type="submit" class="btn btn-primary" onclick="beforeSubmit()">Salvar</button> --}}
                </form>

            </div>
            <div class="modal-footer d-flex justify-content-center bg-primary">
                <div class="d-flex gap-md-4 gap-3 align-items-center">
                    <div class="btn-group">
                        <button id="transpose-down" type="button"
                            class="btn btn-sm border-0 d-flex align-items-center btn-outline-white transpose-down">
                            <span class="material-symbols-outlined">remove</span>
                        </button>
                        <button id="transpose-up" type="button"
                            class="btn btn-sm border-0 d-flex align-items-center btn-outline-white transpose-up">
                            <span class="material-symbols-outlined">add</span>
                        </button>
                        <button id="reset-chords" type="button"
                            class="btn btn-sm border-0 d-flex align-items-center btn-outline-white transpose-up">
                            <span class="material-symbols-outlined">restart_alt</span>
                        </button>
                    </div>

                    <div class="btn-group">
                        <button id="scroll-1x" type="button"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control"
                            data-speed="slow">
                            <span class="material-symbols-outlined">slow_motion_video</span>
                        </button>
                        <button id="scroll-1_5x" type="button"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control"
                            data-speed="normal">
                            <span class="material-symbols-outlined">play_arrow</span>
                        </button>
                        <button id="scroll-2x" type="button"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-control"
                            data-speed="fast">
                            <span class="material-symbols-outlined">fast_forward</span>
                        </button>
                        <button id="scroll-stop" type="button"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-stop">
                            <span class="material-symbols-outlined">pause</span>
                        </button>
                        <button id="scroll-top" type="button"
                            class="btn btn-sm px-sm-1 px-1 border-0 d-flex align-items-center btn-outline-white scroll-to-top">
                            <span class="material-symbols-outlined">keyboard_arrow_up</span>
                        </button>
                    </div>

                    @auth

                        @if(auth()->user()->type_user == 'admin' || auth()->user()->type_user == 'tecnico')
                        <div class="btn-group">
                            <button id="edit-chords" type="button" class="btn btn-sm border-0 d-flex align-items-center btn-outline-white edit-chords-btn">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button id="save-chords" type="button"
                                class="btn btn-sm border-0 align-items-center btn-outline-white save-chords-btn" style="display: none"
                                onclick="beforeSubmit()">
                                <span class="material-symbols-outlined">save</span>
                            </button>
                        </div>
                        @endif
                    @endauth
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

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
    const notesFlat  = ['C', 'Db', 'D', 'Eb', 'E', 'F', 'F#', 'G', 'G#', 'A', 'Bb', 'B'];
    const semitoneMap = {
        'C': 0, 'C#': 1, 'Db': 1,
        'D': 2, 'D#': 3, 'Eb': 3,
        'E': 4, 'Eb': 4, 'F': 5,
        'F': 5, 'F#': 6, 'F#': 6,
        'G': 7, 'G#': 8, 'Ab': 8,
        'A': 9, 'A#': 10, 'Bb': 10,
        'B': 11, 'Bb': 11, 'C': 0
    };

    const wtextarea = document.getElementById('cifraView');

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
        }, 100 / speed);
    }

    function stopScroll() {
        clearInterval(scrollInterval);
    }

    function scrollToTop() {
        wtextarea.scrollTo({ top: 0, behavior: 'smooth' });
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

</script>




@endsection
