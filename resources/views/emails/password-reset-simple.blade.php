<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset de Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            color: #333;
            font-size: 16px;
        }
        .button {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0056b3;
            color: white;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        .warning {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            Sistema Louvor
        </div>

        <div class="content">
            <h2>Olá, {{ $user->name }}!</h2>

            <p>Você solicitou um reset de senha para sua conta no Sistema Louvor.</p>

            <p>Clique no botão abaixo para criar uma nova senha:</p>

            <a href="{{ $resetUrl }}" class="button">Resetar Senha</a>

            <div class="warning">
                <strong>Atenção:</strong> Este link expira em 60 minutos.
            </div>

            <p>Se você não solicitou esta alteração, pode ignorar este e-mail.</p>
        </div>

        <div class="footer">
            <p>Este é um e-mail automático do Sistema Louvor.</p>
            <p>© {{ date('Y') }} - Todos os direitos reservados</p>
        </div>
    </div>
</body>
</html>
