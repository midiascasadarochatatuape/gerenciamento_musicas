<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset de Senha - Sistema Louvor</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
            display: block;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            color: #555;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        .reset-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        .info-box {
            background-color: #e8f4fd;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            margin: 30px 0;
            border-radius: 0 5px 5px 0;
        }
        .info-box p {
            margin: 0;
            color: #2980b9;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .footer .system-name {
            font-weight: 600;
            color: #495057;
        }
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .security-notice p {
            margin: 0;
            color: #856404;
            font-size: 13px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 25px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <span class="icon">🔐</span>
            <h1>Reset de Senha</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Olá, {{ $user->name }}!
            </div>

            <div class="message">
                Você está recebendo este e-mail porque foi solicitado um reset de senha para sua conta no <strong>Sistema Louvor</strong>.
            </div>

            <div class="message">
                Para criar uma nova senha, clique no botão abaixo:
            </div>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔑 Resetar Minha Senha
                </a>
            </div>

            <div class="info-box">
                <p><strong>⏰ Importante:</strong> Este link é válido por apenas 60 minutos por motivos de segurança.</p>
            </div>

            <div class="message">
                Se você não solicitou este reset de senha, pode ignorar este e-mail com segurança. Sua senha atual continuará ativa.
            </div>

            <div class="security-notice">
                <p><strong>🛡️ Dica de Segurança:</strong> Nunca compartilhe este link com outras pessoas. Se você não reconhece esta solicitação, recomendamos que entre em contato conosco imediatamente.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este é um e-mail automático, não responda.</p>
            <p style="margin-top: 15px; font-size: 12px;">
                © {{ date('Y') }} Sistema Louvor. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>
