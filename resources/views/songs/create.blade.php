@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0">Nova Música</h2>
            <button onclick="beforeSubmit('save-and-new')" type="button" class="btn btn-primary text-white px-4 rounded-pill">
                <span class="material-symbols-outlined me-1" style="font-size: 16px;">save_as</span>
                Salvar e Criar Nova
            </button>
        </div>
    </div>

    @if(session()->has('error'))
        <div class="alert alert-danger bg-danger text-white alert-dismissible fade show" role="alert">
            <strong>Erro!</strong> {{ session()->get('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data" id="formEdit">
                        @csrf
                        <input type="hidden" name="status" value="7">
                        <!-- Campos básicos -->
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="title">Título *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="version">Versão</label>
                                    <input type="text" class="form-control" id="version" name="version" value="{{ old('version') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="version">Imagem capa</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Categorias</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-4">
                                            @foreach($categories->where('type', 'category') as $category)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="categories[]" value="{{ $category->id }}" id="category_{{ $category->id }}">
                                                    <label class="form-check-label" for="category_{{ $category->id }}">
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


                            <div class="col-md-1 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="tone">Tom</label>
                                    <input type="text" class="form-control" id="tone" name="tone" value="{{ old('tone') }}">
                                </div>
                            </div>
                            <div class="col-md-1 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="tempo">Tempo</label>
                                    <input type="number" class="form-control" id="tempo" name="tempo" value="{{ old('tempo') }}">
                                </div>
                            </div>
                            <div class="col-md-1 col-4">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="measure">Compasso</label>
                                    <input type="text" class="form-control" id="measure" name="measure" value="{{ old('measure') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="link_youtube">YouTube</label>
                                    <input type="url" class="form-control" id="link_youtube" name="link_youtube" value="{{ old('link_youtube') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="link_spotify">Spotify</label>
                                    <input type="url" class="form-control" id="link_spotify" name="link_spotify" value="{{ old('link_spotify') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="link_drive">Kit Vozes</label>
                                    <input type="url" class="form-control" id="link_drive" name="link_drive" value="{{ old('link_drive') }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-control form-select" id="type" name="type">
                                    <option value="">Selecione o tipo</option>
                                    <option value="hino">Hino</option>
                                    <option value="corinho">Corinho</option>
                                    <option value="cantico">Cântico</option>
                                    <option value="atual">Atual</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="intensity" class="form-label">Intensidade</label>
                                <select class="form-control form-select" id="intensity" name="intensity">
                                    <option value="">Selecione a intensidade</option>
                                    <option value="lenta">Lenta</option>
                                    <option value="media">Média</option>
                                    <option value="rapida">Rápida</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="lyrics">Letra</label>
                                    <textarea class="form-control form-control-plaintext" name="lyrics" id="lyrics" cols="30" rows="9"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="bible_reference" class="form-label">Referências Bíblicas</label>
                                <textarea class="form-control" id="bible_reference" name="bible_reference" rows="9" placeholder="Digite as referências bíblicas aqui">{{ old('bible_reference', $song->bible_reference ?? '') }}</textarea>
                                @error('bible_reference')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="d-flex flex-column" style="height: 500px;">
                                    <label for="cifraInput">Cole a cifra (do Word/Docs):</label><br />
                                    <textarea id="tiny" class="form-control w-100 h-100" name="chord"></textarea>
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
                                    <!-- Tutorial inicial -->
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
                                            <div class="col-md-3">
                                                <label class="form-label">Título (opcional)</label>
                                                <input type="text" class="form-control" name="tutorials[0][title]" placeholder="Ex: Tutorial básico">
                                            </div>
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
                                </div>

                                <button type="button" id="addTutorial" class="btn btn-outline-primary d-flex align-items-center">
                                    <i class="material-symbols-outlined me-1">add</i>
                                    Adicionar Tutorial
                                </button>
                            </div>
                        </div>

                        <!-- Categorias e Contexto -->
                        <div class="row mb-5">

                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select" id="status" name="status">
                                        <option value="7" {{ old('status') == 7 ? 'selected' : '' }}>Aprovada</option>
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Sugestão</option>
                                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Sob análise</option>
                                        <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Excluída</option>
                                        <option value="5" {{ old('status') == 5 ? 'selected' : '' }}>Stand By</option>
                                        <option value="6" {{ old('status') == 6 ? 'selected' : '' }}>Temática</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-center gap-3 my-4">
                            <a href="{{ route('songs.index') }}" class="btn btn-danger px-4 rounded-pill">Cancelar</a>
                            <button onclick="beforeSubmit('save')" type="button" class="btn btn-success text-white px-4 rounded-pill">
                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">save</span>
                                Salvar Música
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Gerenciar Categorias -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.9.1/tinymce.min.js" integrity="sha512-09JpfVm/UE1F4k8kcVUooRJAxVMSfw/NIslGlWE/FGXb2uRO1Nt4BXAJ3LxPqNbO3Hccdu46qaBPp9wVpWAVhA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    tinymce.init({
        selector: 'textarea#tiny',
        menubar: true,
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
                font-size: 12pt;
            }
            pre {
                white-space: pre-wrap !important;
                font-family: monospace !important;
                font-size: 12pt;
                padding: 8px;
                border-radius: 6px;
                margin: 0;
            }
        `
    });    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os tooltips do Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
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

    function beforeSubmit(action = 'save') {

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

        document.getElementById('htmlInput').value = tempDiv.innerHTML;

        // Definir a ação do formulário baseada no botão clicado
        if (action === 'save-and-new') {
            form.action = '{{ route("songs.store-and-new") }}';
        } else {
            form.action = '{{ route("songs.store") }}';
        }

        form.submit();
    }


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
                            <input type="checkbox" class="form-check-input" name="categories[]" value="${data.category.id}" id="category_${data.category.id}">
                            <label class="form-check-label" for="category_${data.category.id}">
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
                            const checkbox = document.getElementById(`category_${categoryId}`);
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
    let tutorialCount = 1;

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
                <div class="col-md-3">
                    <label class="form-label">Título (opcional)</label>
                    <input type="text" class="form-control" name="tutorials[${tutorialCount}][title]" placeholder="Ex: Tutorial básico">
                </div>
                <div class="col-md-4">
                    <label class="form-label">URL do Tutorial</label>
                    <input type="url" class="form-control" name="tutorials[${tutorialCount}][url]" placeholder="https://youtube.com/...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger remove-tutorial">
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



