<!DOCTYPE html>
<html>
<head>
    <title>Teste Tutoriais</title>
</head>
<body>
    <h2>Teste de Envio de Tutoriais</h2>

    <form method="POST" action="/test-tutorials">
        @csrf
        <h3>Tutorial 1</h3>
        <label>Instrumento:</label>
        <select name="tutorials[0][instrument]">
            <option value="">Selecione</option>
            <option value="Guitarra">Guitarra</option>
            <option value="Violão">Violão</option>
        </select><br><br>

        <label>Título:</label>
        <input type="text" name="tutorials[0][title]" value="Tutorial teste"><br><br>

        <label>URL:</label>
        <input type="url" name="tutorials[0][url]" value="https://youtube.com/teste"><br><br>

        <h3>Tutorial 2</h3>
        <label>Instrumento:</label>
        <select name="tutorials[1][instrument]">
            <option value="">Selecione</option>
            <option value="Guitarra">Guitarra</option>
            <option value="Violão">Violão</option>
        </select><br><br>

        <label>Título:</label>
        <input type="text" name="tutorials[1][title]" value=""><br><br>

        <label>URL:</label>
        <input type="url" name="tutorials[1][url]" value="https://youtube.com/teste2"><br><br>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>
