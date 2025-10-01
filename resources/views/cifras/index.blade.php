@extends('layouts.app')

@section('content')


<div class="container">
    <form action="{{ route('cifras.store') }}" method="POST">
        @csrf
        <div>
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo">
        </div>


        <label for="cifraInput">Cole a cifra (do Word/Docs):</label><br />
        <div contenteditable="true" class="form-control w-100" id="cifraInput" style="border: 1px solid #ccc; padding: 10px; min-height: 300px;"></div>

        <input type="hidden" name="html" id="htmlInput" />



        <button type="submit" class="btn btn-primary mt-4" onclick="beforeSubmit()">Salvar</button>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.9.1/tinymce.min.js" integrity="sha512-09JpfVm/UE1F4k8kcVUooRJAxVMSfw/NIslGlWE/FGXb2uRO1Nt4BXAJ3LxPqNbO3Hccdu46qaBPp9wVpWAVhA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    tinymce.init({
        selector: '#cifraInput'
    });

    function beforeSubmit() {

        //let editor = document.getElementById('cifraInput');
        let content = tinymce.get('cifraInput').getContent();
        var tempDiv = document.createElement('div');

        tempDiv.innerHTML = content;

        // Marca negrito com uma classe específica (vai ser vermelha depois)
        tempDiv.querySelectorAll('b, strong').forEach(el => el.classList.add('cifra-chord'));
        // Salva como HTML puro
        document.getElementById('htmlInput').value = tempDiv.innerHTML;
    }
</script>


@endsection
