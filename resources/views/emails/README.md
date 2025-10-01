# Templates de E-mail para Reset de Senha

Este projeto contém templates personalizados para e-mails de reset de senha.

## Arquivos Criados

### 1. Template Principal (Premium)
- **Arquivo**: `resources/views/emails/password-reset.blade.php`
- **Características**:
  - Design moderno com gradientes
  - Responsivo para mobile
  - Ícones e cores personalizadas
  - Avisos de segurança
  - Animações CSS (hover effects)

### 2. Template Simples (Clean)
- **Arquivo**: `resources/views/emails/password-reset-simple.blade.php`
- **Características**:
  - Design minimalista
  - Carregamento rápido
  - Cores simples e limpas
  - Fácil personalização

### 3. Notificação Personalizada
- **Arquivo**: `app/Notifications/MyPasswordResetNotification.php`
- **Modificado para usar**: Template personalizado em vez do padrão do Laravel

## Como Usar

### Para usar o template premium (padrão atual):
```php
// Já está configurado na MyPasswordResetNotification.php
->view('emails.password-reset', [
    'user' => $notifiable,
    'resetUrl' => $resetUrl,
    'token' => $this->token
]);
```

### Para usar o template simples:
Substitua na `MyPasswordResetNotification.php`:
```php
->view('emails.password-reset-simple', [
    'user' => $notifiable,
    'resetUrl' => $resetUrl,
    'token' => $this->token
]);
```

## Variáveis Disponíveis nos Templates

- `$user` - Objeto do usuário (name, email, etc.)
- `$resetUrl` - URL completa para reset de senha
- `$token` - Token de reset (caso precise)

## Personalização

### Cores e Estilo
No template premium, você pode modificar:
- **Gradiente principal**: `#667eea` e `#764ba2`
- **Cor do botão**: Mesmo gradiente
- **Cores de aviso**: `#fff3cd` (fundo) e `#856404` (texto)

### Conteúdo
- Modifique o texto diretamente nos arquivos `.blade.php`
- Adicione ou remova seções conforme necessário
- Customize o footer com suas informações

### Logo/Ícone
- No template premium: Emoji 🔐 (linha 92)
- No template simples: Texto "Sistema Louvor"
- Substitua por sua logo se necessário

## Teste

Para testar o template, use a rota de teste:
```
GET /test-email
```

Ou teste o reset de senha:
1. Vá para `/password/reset`
2. Digite um e-mail válido
3. Verifique a caixa de entrada

## Configuração do E-mail

Certifique-se de que o `.env` está configurado:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-de-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="Sistema Louvor"
```

## Troubleshooting

### E-mail não está enviando:
1. Verifique configurações do `.env`
2. Verifique se o Gmail permite "apps menos seguros" ou use senha de app
3. Teste com a rota `/test-email`

### Template não está sendo usado:
1. Limpe o cache: `php artisan cache:clear`
2. Limpe views: `php artisan view:clear`
3. Verifique se a notificação está usando a view correta

### Personalização não aparece:
1. Force refresh (Ctrl+F5)
2. Teste em cliente de e-mail diferente
3. Alguns clientes filtram CSS externo

## Suporte

- Templates são compatíveis com Gmail, Outlook, Apple Mail
- CSS inline para máxima compatibilidade
- Testado em dispositivos móveis e desktop
