@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Editar Música: {{ $song->title }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('songs.update', $song->id) }}" method="POST" enctype="multipart/form-data" id="formEdit" onsubmit="return beforeSubmit();">
                        @csrf
                        @method('PUT')

                        <!-- Campos básicos -->
                        <div class="row mb-3">
                            <div class="col-lg-5 col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="title">Título *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $song->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="version">Versão</label>
                                    <input type="text" class="form-control" id="version" name="version"
                                           value="{{ old('version', $song->version) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3 d-flex flex-column">
                                    <label class="form-label" for="version">Imagem capa @if($song->image) <a href="#!" id="alt-perfil" class="small">(Alterar capa)</a> @endif</label>
                                     @if($song->image)
                                        <img src="{{ $song->image }}" id="image-perfil" class="foto-perfil-edit @if($song->image) d-block @endif" alt="">
                                    @endif
                                    <input type="file" name="image" id="image" class="form-control @if($song->image) d-none @endif" value="">

                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="tone">Tom</label>
                                    <input type="text" class="form-control" id="tone" name="tone"
                                           value="{{ old('tone', $song->tone) }}">
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="tempo">Tempo</label>
                                    <input type="number" class="form-control" id="tempo" name="tempo"
                                           value="{{ old('tempo', $song->tempo) }}">
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="measure">Compasso</label>
                                    <input type="text" class="form-control" id="measure" name="measure"
                                           value="{{ old('measure', $song->measure) }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="link_youtube">YouTube</label>
                                    <input type="url" class="form-control" id="link_youtube" name="link_youtube"
                                           value="{{ old('link_youtube', $song->link_youtube) }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="link_spotify">Spotify</label>
                                    <input type="url" class="form-control" id="link_spotify" name="link_spotify"
                                           value="{{ old('link_spotify', $song->link_spotify) }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="link_drive">Kit Vozes</label>
                                    <input type="url" class="form-control" id="link_drive" name="link_drive"
                                           value="{{ old('link_drive', $song->link_drive) }}">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-control form-select" id="type" name="type">
                                    <option value="hino" {{ old('type', $song->type) == '' ? 'selected' : '' }}>Selecione o tipo</option>
                                    <option value="hino" {{ old('type', $song->type) == 'hino' ? 'selected' : '' }}>Hino</option>
                                    <option value="corinho" {{ old('type', $song->type) == 'corinho'?'selected' : '' }}>Corinho</option>
                                    <option value="cantico" {{ old('type', $song->type) == 'cantico'?'selected' : '' }}>Cântico</option>
                                    <option value="atual" {{ old('type', $song->type) == 'atual'?'selected' : '' }}>Atual</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="intensity" class="form-label">Intensidade</label>
                                <select class="form-control form-select" id="intensity" name="intensity">
                                    <option value="lenta" {{ old('intensity', $song->intensity) == '' ? 'selected' : '' }}>Selecione a intensidade</option>
                                    <option value="lenta" {{ old('intensity', $song->intensity) == 'lenta' ? 'selected' : '' }}>Lenta</option>
                                    <option value="media" {{ old('intensity', $song->intensity) == 'media'?'selected' : '' }}>Média</option>
                                    <option value="rapida" {{ old('intensity', $song->intensity) == 'rapida'?'selected' : '' }}>Rápida</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="lyrics">Letra</label>
                                    <textarea class="form-control @error('lyrics') is-invalid @enderror" id="lyrics"
                                              name="lyrics" cols="30" rows="9">{{ old('lyrics', $song->lyrics) }}</textarea>
                                    @error('lyrics')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="bible_reference" class="form-label">Referências Bíblicas</label>
                                <textarea class="form-control" id="bible_reference" name="bible_reference" rows="9" placeholder="Digite as referências bíblicas aqui">{{ old('bible_reference', $song->bible_reference) }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex flex-column" style="height: 500px;">
                                    <label for="cifraInput">Cole a cifra (do Word/Docs):</label><br />
                                    <textarea id="tiny" class="form-control w-100 h-100" name="chord">{!! $song->chords !!}</textarea>
                                    <input type="hidden" name="chords" id="htmlInput" />
                                </div>
                            </div>
                        </div>

                        <!-- Seção de Tutoriais -->
                        <div class="row mb-5">
                            <div class="col-12">
                                <h5 class="mb-3">Tutoriais</h5>
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="material-symbols-outlined me-2">info</i>
                                    Adicione links de tutoriais para diferentes instrumentos. Você pode adicionar múltiplos tutoriais.
                                </div>

                                <div id="tutorialsContainer">
                                    @if($song->tutorials && $song->tutorials->count() > 0)
                                        @foreach($song->tutorials as $index => $tutorial)
                                            <div class="tutorial-item mb-3 border rounded p-3">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Instrumento</label>
                                                        <select class="form-control form-select" name="tutorials[{{ $index }}][instrument]">
                                                            <option value="">Selecione o instrumento</option>
                                                            <option value="Guitarra" {{ $tutorial->instrument == 'Guitarra' ? 'selected' : '' }}>Guitarra</option>
                                                            <option value="Teclado" {{ $tutorial->instrument == 'Teclado' ? 'selected' : '' }}>Teclado</option>
                                                            <option value="Violão" {{ $tutorial->instrument == 'Violão' ? 'selected' : '' }}>Violão</option>
                                                            <option value="Bateria" {{ $tutorial->instrument == 'Bateria' ? 'selected' : '' }}>Bateria</option>
                                                            <option value="Baixo" {{ $tutorial->instrument == 'Baixo' ? 'selected' : '' }}>Baixo</option>
                                                            <option value="Sopro" {{ $tutorial->instrument == 'Sopro' ? 'selected' : '' }}>Sopro</option>
                                                            <option value="Cordas" {{ $tutorial->instrument == 'Cordas' ? 'selected' : '' }}>Cordas</option>
                                                        </select>
                                                    </div>
                                                    <!-- div class="col-md-3">
                                                        <label class="form-label">Título (opcional)</label>
                                                        <input type="text" class="form-control" name="tutorials[{{ $index }}][title]" value="{{ $tutorial->title }}" placeholder="Ex: Tutorial básico">
                                                    </div -->
                                                    <div class="col-md-4">
                                                        <label class="form-label">URL do Tutorial</label>
                                                        <input type="url" class="form-control" name="tutorials[{{ $index }}][url]" value="{{ $tutorial->url }}" placeholder="https://youtube.com/...">
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-outline-danger px-4 d-flex align-items-center remove-tutorial">
                                                            <i class="material-symbols-outlined">delete</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Tutorial inicial vazio -->
                                        <div class="tutorial-item mb-3 border rounded p-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Instrumento</label>
                                                    <select class="form-control form-select" name="tutorials[0][instrument]">
                                                        <option value="">Selecione o instrumento</option>
                                                        <option value="Guitarra">Guitarra</option>
                                                        <option value="Teclado">Teclado</option>
                                                        <option value="Violão">Violão</option>
                                                        <option value="Bateria">Bateria</option>
                                                        <option value="Baixo">Baixo</option>
                                                        <option value="Sopro">Sopro</option>
                                                        <option value="Cordas">Cordas</option>
                                                    </select>
                                                </div>

                                                <!-- div class="col-md-3">
                                                    <label class="form-label">Título (opcional)</label>
                                                    <input type="text" class="form-control" name="tutorials[0][title]" placeholder="Ex: Tutorial básico">
                                                </div -->
                                                <div class="col-md-4">
                                                    <label class="form-label">URL do Tutorial</label>
                                                    <input type="url" class="form-control" name="tutorials[0][url]" placeholder="https://youtube.com/...">
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn px-4 btn-outline-danger d-flex align-items-center remove-tutorial">
                                                        <i class="material-symbols-outlined">delete</i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <button type="button" id="addTutorial" class="btn btn-outline-primary d-flex align-items-center">
                                    <i class="material-symbols-outlined me-1">add</i>
                                    Adicionar Tutorial
                                </button>
                            </div>
                        </div>

                        <!-- Categorias e Contexto -->
                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <label class="form-label">Categorias</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-4">
                                            @foreach($categories->where('type', 'category') as $category)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" {{ in_array($category->id, old('categories', $song->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill mt-4 d-inline-block" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                                            Gerenciar Categorias
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select" id="status" name="status">
                                        <option value="7" {{ old('status', $song->status) == 7 ? 'selected' : '' }}>Aprovada</option>
                                        <option value="1" {{ old('status', $song->status) == 1 ? 'selected' : '' }}>Sugestão</option>
                                        <option value="2" {{ old('status', $song->status) == 2 ? 'selected' : '' }}>Sob análise</option>
                                        <option value="3" {{ old('status', $song->status) == 3 ? 'selected' : '' }}>Excluída</option>
                                        <option value="5" {{ old('status', $song->status) == 5 ? 'selected' : '' }}>Stand By</option>
                                        <option value="6" {{ old('status', $song->status) == 6 ? 'selected' : '' }}>Temática</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">

                        </div>



                        <!-- Botões -->
                        <div class="d-flex justify-content-center gap-4 my-4">
                            <a href="{{ route('songs.show', $song) }}" class="btn btn-danger px-4 rounded-pill">Cancelar</a>
                            <button type="submit" class="btn btn-success text-white px-4 rounded-pill">Atualizar Música</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.9.1/tinymce.min.js" integrity="sha512-09JpfVm/UE1F4k8kcVUooRJAxVMSfw/NIslGlWE/FGXb2uRO1Nt4BXAJ3LxPqNbO3Hccdu46qaBPp9wVpWAVhA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    tinymce.init({
        selector: 'textarea#tiny',
        menubar: true   ,
        toolbar: 'bold italic underline | code',
        forced_root_block: false, // evita que o TinyMCE coloque <p>

        setup: (editor) => {
            // Intercepta eventos de colagem para limpar apenas fonte e tamanho
            editor.on('PastePreProcess', (e) => {
                // Remove apenas formatação de fonte, mantendo estrutura e outras formatações
                e.content = e.content.replace(/font-family:[^;]*;?/gi, '');
                e.content = e.content.replace(/font-size:[^;]*;?/gi, '');
                e.content = e.content.replace(/<font[^>]*>/gi, '');
                e.content = e.content.replace(/<\/font>/gi, '');
            });
        },

        content_style: `
            body {
                font-family: monospace !important;
                font-size: 12pt !important;
                line-height: 1.4;
            }
            * {
                font-family: monospace !important;
                font-size: 12pt !important;
            }
            p, div, span, strong, em, b, i, u {
                font-family: monospace !important;
                font-size: 12pt
            }
            pre {
                white-space: pre-wrap !important;
                font-family: monospace !important;
                font-size: 12pt
                padding: 8px;
                border-radius: 6px;
                margin: 0;
            }
        `
    });
    document.getElementById('alt-perfil').addEventListener('click', function() {
        let im = document.getElementById('image');
        document.getElementById('image-perfil').classList.toggle('d-none');
        im.classList.toggle('d-none')
        this.innerHTML = this.innerHTML == '(Alterar capa)'? '(Desfazer)' : '(Alterar capa)';
    });

    document.addEventListener('DOMContentLoaded', function() {
        const lyricsTextarea = document.getElementById('lyrics');

        function autoResize(textarea) {
            textarea.style.height = 'auto';
            /* textarea.style.height = textarea.scrollHeight + 'px'; */
        }

        if (lyricsTextarea) {
            lyricsTextarea.addEventListener('input', function() {
                autoResize(this);
            });
            autoResize(lyricsTextarea);
        }
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

        // Não chamar form.submit() aqui, deixar o envio normal continuar
        return true;

    }
</script>
<!-- Modal para Gerenciar Categorias -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoriesModalLabel">Gerenciar Categorias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Nova Categoria</h5>
                            </div>
                            <div class="card-body">
                                <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="category">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Adicionar Categoria</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5 class="mb-0">Categorias Existentes</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <div class="list-group" id="categoriesList">
                                        @foreach($categories->where('type', 'category') as $category)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $category->name }}
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline category-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manipular o envio do formulário de categoria via AJAX
        const categoryForm = document.getElementById('categoryForm');
        if (categoryForm) {
            categoryForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Adicionar a nova categoria à lista
                        const categoriesList = document.getElementById('categoriesList');
                        const newCategory = document.createElement('div');
                        newCategory.className = 'list-group-item d-flex justify-content-between align-items-center';
                        newCategory.innerHTML = `
                            ${data.category.name}
                            <form action="{{ route('categories.store') }}/${data.category.id}" method="POST" class="d-inline category-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                    Excluir
                                </button>
                            </form>
                        `;
                        categoriesList.appendChild(newCategory);

                        // Adicionar a nova categoria às opções de checkbox
                        const categoriesContainer = document.querySelector('.d-flex.flex-wrap.gap-4');
                        const newCheckbox = document.createElement('div');
                        newCheckbox.className = 'form-check';
                        newCheckbox.innerHTML = `
                            <input type="checkbox" class="form-check-input" name="categories[]" value="${data.category.id}" id="category${data.category.id}">
                            <label class="form-check-label" for="category${data.category.id}">
                                ${data.category.name}
                            </label>
                        `;
                        categoriesContainer.appendChild(newCheckbox);

                        // Limpar o formulário
                        categoryForm.reset();
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
            });
        }

        // Manipular exclusão de categoria via AJAX
        const categoryDeleteForms = document.querySelectorAll('.category-delete-form');
        categoryDeleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (confirm('Tem certeza que deseja excluir esta categoria?')) {
                    const categoryItem = this.closest('.list-group-item');
                    const categoryId = this.action.split('/').pop();

                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remover o item da lista
                            categoryItem.remove();

                            // Remover o checkbox correspondente
                            const checkbox = document.getElementById(`category${categoryId}`);
                            if (checkbox) {
                                checkbox.closest('.form-check').remove();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    });
                }
            });
        });
    });

    // Gerenciamento de tutoriais
    let tutorialCount = {{ $song->tutorials ? $song->tutorials->count() : 1 }};

    document.getElementById('addTutorial').addEventListener('click', function() {
        const container = document.getElementById('tutorialsContainer');
        const newTutorial = document.createElement('div');
        newTutorial.className = 'tutorial-item mb-3 border rounded p-3';
        newTutorial.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Instrumento</label>
                    <select class="form-control form-select" name="tutorials[${tutorialCount}][instrument]">
                        <option value="">Selecione o instrumento</option>
                        <option value="Guitarra">Guitarra</option>
                        <option value="Teclado">Teclado</option>
                        <option value="Violão">Violão</option>
                        <option value="Bateria">Bateria</option>
                        <option value="Baixo">Baixo</option>
                        <option value="Sopro">Sopro</option>
                        <option value="Cordas">Cordas</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">URL do Tutorial</label>
                    <input type="url" class="form-control" name="tutorials[${tutorialCount}][url]" placeholder="https://youtube.com/...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger px-4 d-flex align-items-center remove-tutorial">
                        <i class="material-symbols-outlined">delete</i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newTutorial);
        tutorialCount++;
    });

    // Remover tutorial
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-tutorial')) {
            const tutorialItem = e.target.closest('.tutorial-item');
            if (document.querySelectorAll('.tutorial-item').length > 1) {
                tutorialItem.remove();
            } else {
                // Limpar os campos se for o último tutorial
                tutorialItem.querySelectorAll('select, input').forEach(field => field.value = '');
            }
        }
    });
</script>

@endsection
