<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Louvor - Documentação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .endpoint {
            background: #e3f2fd;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin: 5px 0;
        }
        .method {
            background: #4caf50;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-5">🎵 API Louvor - Documentação</h1>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>📋 Informações Gerais</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Base URL:</strong> <code>{{ url('/') }}/api</code></p>
                        <p><strong>Versão:</strong> 1.0.0</p>
                        <p><strong>Formato de Resposta:</strong> JSON</p>
                        <p><strong>Autenticação:</strong> Opcional (Token Bearer ou parâmetro ?token=)</p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3>🔗 Endpoints Disponíveis</h3>
                    </div>
                    <div class="card-body">

                        <h4>📊 Informações da API</h4>
                        <div class="endpoint">
                            <span class="method">GET</span> <code>/api/info</code>
                        </div>
                        <p>Retorna informações gerais da API e lista de endpoints.</p>

                        <h4>📅 Escalas</h4>
                        <div class="endpoint">
                            <span class="method">GET</span> <code>/api/schedules</code>
                        </div>
                        <p>Lista todas as escalas com filtros opcionais.</p>

                        <div class="endpoint">
                            <span class="method">GET</span> <code>/api/schedules/{id}</code>
                        </div>
                        <p>Retorna uma escala específica com detalhes completos.</p>

                        <h4>👥 Grupos</h4>
                        <div class="endpoint">
                            <span class="method">GET</span> <code>/api/groups</code>
                        </div>
                        <p>Lista todos os grupos com seus membros.</p>

                        <div class="endpoint">
                            <span class="method">GET</span> <code>/api/groups/{id}</code>
                        </div>
                        <p>Retorna informações de um grupo específico.</p>

                        <h4>🎵 Músicas</h4>
                        <div class="endpoint">
                            <span class="method">GET</span> <code>/api/songs</code>
                        </div>
                        <p>Lista todas as músicas aprovadas.</p>

                        <div class="endpoint">
                            <span class="method">GET</span> <code>/api/songs/{id}</code>
                        </div>
                        <p>Retorna uma música específica com letras e cifras.</p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3>🔍 Parâmetros de Filtro</h3>
                    </div>
                    <div class="card-body">
                        <h4>Escalas (/api/schedules)</h4>
                        <ul>
                            <li><code>group_id</code> - Filtrar por ID do grupo</li>
                            <li><code>date_from</code> - Data inicial (formato: Y-m-d)</li>
                            <li><code>date_to</code> - Data final (formato: Y-m-d)</li>
                            <li><code>upcoming</code> - true para escalas futuras apenas</li>
                        </ul>

                        <h4>Músicas (/api/songs)</h4>
                        <ul>
                            <li><code>search</code> - Busca por título, versão ou referência bíblica</li>
                            <li><code>version</code> - Filtrar por versão/intérprete</li>
                            <li><code>type</code> - Filtrar por tipo (hino, corinho, cantico, atual)</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3>💡 Exemplos de Uso</h3>
                    </div>
                    <div class="card-body">

                        <h4>1. Listar todas as escalas</h4>
                        <div class="code-block">
                            <code>GET {{ url('/') }}/api/schedules</code>
                        </div>

                        <h4>2. Escalas futuras de um grupo específico</h4>
                        <div class="code-block">
                            <code>GET {{ url('/') }}/api/schedules?group_id=1&upcoming=true</code>
                        </div>

                        <h4>3. Escalas entre datas</h4>
                        <div class="code-block">
                            <code>GET {{ url('/') }}/api/schedules?date_from=2025-11-01&date_to=2025-12-31</code>
                        </div>

                        <h4>4. Buscar músicas</h4>
                        <div class="code-block">
                            <code>GET {{ url('/') }}/api/songs?search=aleluia</code>
                        </div>

                        <h4>5. Músicas por versão/intérprete</h4>
                        <div class="code-block">
                            <code>GET {{ url('/') }}/api/songs?version=Diante%20do%20Trono</code>
                        </div>

                        <h4>6. Músicas por tipo</h4>
                        <div class="code-block">
                            <code>GET {{ url('/') }}/api/songs?type=atual</code>
                        </div>

                        <h4>6. Com autenticação (se habilitada)</h4>
                        <div class="code-block">
                            <code>GET {{ url('/') }}/api/schedules?token=seu_token_aqui</code><br>
                            ou<br>
                            <code>Authorization: Bearer seu_token_aqui</code>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3>📋 Formato de Resposta</h3>
                    </div>
                    <div class="card-body">
                        <h4>Resposta de Sucesso</h4>
                        <div class="code-block">
<pre>{
  "success": true,
  "data": [...],
  "total": 10,
  "generated_at": "2025-11-03T15:30:00.000000Z"
}</pre>
                        </div>

                        <h4>Resposta de Erro</h4>
                        <div class="code-block">
<pre>{
  "success": false,
  "message": "Descrição do erro",
  "error": "Detalhes técnicos"
}</pre>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3>🧪 Testador da API</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="endpoint" class="form-label">Endpoint:</label>
                            <select class="form-select" id="endpoint">
                                <option value="/api/info">GET /api/info</option>
                                <option value="/api/schedules">GET /api/schedules</option>
                                <option value="/api/groups">GET /api/groups</option>
                                <option value="/api/songs">GET /api/songs</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="params" class="form-label">Parâmetros (opcional):</label>
                            <input type="text" class="form-control" id="params" placeholder="Ex: ?group_id=1&upcoming=true">
                        </div>
                        <button class="btn btn-primary" onclick="testAPI()">Testar API</button>
                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function testAPI() {
            const endpoint = document.getElementById('endpoint').value;
            const params = document.getElementById('params').value;
            const url = `{{ url('/') }}${endpoint}${params}`;

            try {
                const response = await fetch(url);
                const data = await response.json();

                document.getElementById('result').innerHTML = `
                    <div class="alert alert-success">
                        <h5>Resposta (${response.status}):</h5>
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                    </div>
                `;
            } catch (error) {
                document.getElementById('result').innerHTML = `
                    <div class="alert alert-danger">
                        <h5>Erro:</h5>
                        <pre>${error.message}</pre>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
