@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Novo Devocional</h2>

            <form method="POST" action="{{ route('devocionais.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <!-- Título -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Título *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Resumo -->
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Resumo</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                      id="excerpt" name="excerpt" rows="3"
                                      placeholder="Breve descrição do devocional (opcional)">{{ old('excerpt') }}</textarea>
                            <div class="form-text">Deixe em branco para gerar automaticamente do conteúdo.</div>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Conteúdo -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Conteúdo *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="15" style="min-height: 400px;" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Data do Devocional -->
                        <div class="mb-3">
                            <label for="devotional_date" class="form-label">Data do Devocional *</label>
                            <input type="date" class="form-control @error('devotional_date') is-invalid @enderror"
                                   id="devotional_date" name="devotional_date"
                                   value="{{ old('devotional_date', date('Y-m-d')) }}" required>
                            @error('devotional_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Imagem -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagem</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">JPG, PNG, GIF (máx. 2MB)</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Referências Bíblicas -->
                        <div class="mb-3">
                            <label class="form-label">Referências Bíblicas</label>
                            <div id="bible-references-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="bible_references[]"
                                           placeholder="Ex: João 3:16" value="{{ old('bible_references.0') }}">
                                    <button type="button" class="btn btn-outline-danger remove-reference" style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-reference">
                                <i class="fas fa-plus"></i> Adicionar Referência
                            </button>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published"
                                       name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publicar imediatamente
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('devocionais.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar Devocional
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.9.1/tinymce.min.js" integrity="sha512-09JpfVm/UE1F4k8kcVUooRJAxVMSfw/NIslGlWE/FGXb2uRO1Nt4BXAJ3LxPqNbO3Hccdu46qaBPp9wVpWAVhA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    tinymce.init({
        selector: 'textarea#content',
    });

    // Referências Bíblicas - Adicionar/Remover
    document.getElementById('add-reference').addEventListener('click', function() {
        const container = document.getElementById('bible-references-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" name="bible_references[]" placeholder="Ex: João 3:16">
            <button type="button" class="btn btn-outline-danger remove-reference">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);

        // Mostrar botões de remover se houver mais de um campo
        updateRemoveButtons();
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-reference') || e.target.parentElement.classList.contains('remove-reference')) {
            const button = e.target.classList.contains('remove-reference') ? e.target : e.target.parentElement;
            button.closest('.input-group').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const container = document.getElementById('bible-references-container');
        const groups = container.querySelectorAll('.input-group');
        groups.forEach((group, index) => {
            const removeBtn = group.querySelector('.remove-reference');
            if (groups.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
</script>
@endsection
