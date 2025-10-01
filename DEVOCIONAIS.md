# Sistema de Devocionais - Louvor

## 📖 Visão Geral

O sistema de devocionais foi criado para permitir que administradores e técnicos do sistema Louvor criem e gerenciem conteúdo devocional (semelhante a um blog) para a comunidade.

## 🚀 Funcionalidades Principais

### ✍️ **Criação e Edição**
- **Editor de texto rico** (TinyMCE) com formatação completa
- **Upload de imagens** para ilustrar os devocionais
- **Referências bíblicas** múltiplas por devocional
- **Sistema de rascunhos** e publicação
- **Resumo automático** ou manual

### 👥 **Controle de Acesso**
- **Gestão**: Apenas admins e técnicos
- **Visualização pública**: Todos os usuários (área "Blog")
- **Autorização baseada em policies**

### 📊 **Recursos Avançados**
- **Sistema de visualizações** (contador de acessos)
- **Busca por conteúdo** e título
- **Filtros por status** (publicado/rascunho)
- **URLs amigáveis** (slugs)
- **Paginação** otimizada
- **Responsive design**

## 🔧 Estrutura Técnica

### **Database**
- **Tabela**: `devocionals`
- **Campos principais**:
  - `title` - Título do devocional
  - `slug` - URL amigável (gerada automaticamente)
  - `content` - Conteúdo com formatação HTML
  - `excerpt` - Resumo (manual ou automático)
  - `image` - Imagem de capa
  - `bible_references` - Array JSON com referências
  - `devotional_date` - Data do devocional
  - `is_published` - Status de publicação
  - `views` - Contador de visualizações

### **Rotas**
```php
// Área administrativa (admins/técnicos)
/devocionais              # Lista para gestão
/devocionais/create       # Formulário de criação
/devocionais/{id}         # Visualização individual
/devocionais/{id}/edit    # Formulário de edição

// Área pública (todos os usuários)
/blog                     # Lista pública
/blog/{slug}             # Visualização pública
```

## 📝 Como Usar

### **Para Administradores/Técnicos**

1. **Acessar o Menu**
   - Entre no sistema
   - Clique em "Devocionais" no menu superior

2. **Criar Novo Devocional**
   - Clique em "Novo Devocional"
   - Preencha título e conteúdo
   - Use o editor rico para formatação
   - Adicione referências bíblicas
   - Escolha uma imagem (opcional)
   - Marque "Publicar" ou deixe como rascunho

3. **Gerenciar Devocionais**
   - Use filtros para encontrar conteúdo
   - Edite devocionais existentes
   - Controle publicação/rascunhos
   - Acompanhe visualizações

### **Para Usuários Comuns**

1. **Acessar Blog Público**
   - Clique em "Blog" no menu
   - Ou acesse diretamente `/blog`

2. **Navegar Conteúdo**
   - Use a busca para encontrar tópicos
   - Leia devocionais publicados
   - Compartilhe conteúdo
   - Veja devocionais relacionados

## 🎨 Editor de Texto Rico

O editor TinyMCE oferece:
- **Formatação**: Negrito, itálico, sublinhado
- **Títulos**: H1, H2, H3, etc.
- **Listas**: Numeradas e com marcadores
- **Alinhamento**: Esquerda, centro, direita, justificado
- **Links e âncoras**
- **Citações** em bloco
- **Código** e texto pré-formatado

## 📚 Referências Bíblicas

- Adicione quantas referências precisar
- Formato sugerido: "João 3:16", "Salmos 23:1-6"
- Aparecem em destaque no devocional
- Facilitam estudo e referência

## 🔒 Segurança

- **Middleware customizado** para controle de acesso
- **Policies** para autorização granular
- **Validação** de dados no servidor
- **Upload seguro** de imagens
- **Sanitização** de conteúdo HTML

## 📈 Métricas

- **Contador de visualizações** automático
- **Data de criação** e última atualização
- **Status de publicação** visível
- **Autor** do devocional registrado

## 🛠️ Dados de Exemplo

O sistema vem com devocionais de exemplo:
```bash
php artisan db:seed --class=DevocionalSeeder
```

## 📱 Design Responsivo

- **Interface adaptativa** para mobile e desktop
- **Cards responsivos** na listagem
- **Leitura otimizada** em dispositivos móveis
- **Menu colapsível** em telas pequenas

## 🚀 Próximas Funcionalidades Sugeridas

- [ ] **Categorias** de devocionais
- [ ] **Tags** para classificação
- [ ] **Comentários** dos usuários
- [ ] **Favoritos** pessoais
- [ ] **Notificações** de novos devocionais
- [ ] **Export PDF** de devocionais
- [ ] **Agenda** de publicações
- [ ] **Templates** pré-definidos

---

## 📞 Suporte

Para dúvidas sobre o uso do sistema:
1. Consulte a documentação
2. Entre em contato com o administrador
3. Verifique as permissões de usuário

**Desenvolvido com ❤️ para o sistema Louvor**
