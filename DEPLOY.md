# Deploy

Este documento descreve o processo recomendado para publicar o projeto **Despachante Digital Flow** em ambiente de produção.

O objetivo é garantir que a landing page funcione corretamente em servidores reais, mantendo desempenho, segurança e facilidade de manutenção.

---

# Requisitos do Servidor

O ambiente de produção deve possuir:

- PHP 7.4 ou superior
- MySQL ou MariaDB
- Apache ou Nginx
- HTTPS habilitado
- WordPress 6 ou superior

Recomendado:

- PHP 8+
- Redis ou cache de página
- CDN (Cloudflare)

---

# Estrutura do Projeto em Produção

O tema deve estar localizado em:

wp-content/themes/despachante/

Estrutura básica:

wp-content  
└── themes  
    └── despachante  
        ├── assets  
        │   ├── css  
        │   │   └── estyle.css  
        │   └── js  
        │       └── handlePreAnalise.js  
        │
        ├── functions.php  
        ├── header.php  
        ├── footer.php  
        ├── index.php  
        ├── style.css  
        │
        ├── README.md  
        ├── INSTALL.md  
        ├── CHANGELOG.md  
        └── DEPLOY.md  

---

# Passo 1 — Subir WordPress

Instale WordPress normalmente no servidor.

Exemplo de instalação:

https://seudominio.com

Após instalação finalize:

- configuração do banco
- criação do usuário administrador

---

# Passo 2 — Instalar o Tema

Copie a pasta do tema para:

wp-content/themes/

Exemplo:

wp-content/themes/despachante

---

# Passo 3 — Ativar o Tema

No painel WordPress:

Aparência → Temas

Ative:

Despachante Digital Flow

---

# Passo 4 — Instalar Plugin de Avaliações

Para exibir avaliações reais do Google, instale:

WP Google Review Slider

Link:

https://wordpress.org/plugins/wp-google-review-slider/

No painel:

Plugins → Adicionar novo

Procure por:

WP Google Review Slider

Instale e ative.

---

# Passo 5 — Configurar Avaliações

No painel:

Google Reviews → Create Slider

Configure a integração com o Google Business.

Depois copie o shortcode gerado.

Exemplo:

[wp-review-slider id="1"]

---

# Passo 6 — Inserir Shortcode no Tema

No painel:

Aparência → Personalizar → Avaliações Google

Cole o shortcode gerado pelo plugin.

Exemplo:

[wp-review-slider id="1"]

---

# Passo 7 — Configurar Hero

No painel:

Aparência → Personalizar → Hero

Configure:

- imagem de fundo
- título
- subtítulo
- texto do botão
- link do botão

Exemplo:

Título  
Despachante Veicular com Atendimento Rápido

Subtítulo  
Transferência, licenciamento e regularização de documentos.

Botão  
Solicitar Atendimento

Link  
#pre-analise

---

# Passo 8 — Cadastrar Serviços

No painel:

Serviços → Adicionar novo

Informe:

- título
- descrição
- ícone FontAwesome

Exemplos de ícones:

fas fa-car-side  
fas fa-exchange-alt  
fas fa-calendar-check  
fas fa-unlock  
fas fa-copy  
fas fa-cogs  

---

# Passo 9 — Cadastrar FAQ

No painel:

FAQ → Adicionar novo

Informe:

- pergunta
- resposta

Essas perguntas aparecerão automaticamente na landing.

---

# Passo 10 — Testar Landing Page

Acesse a página inicial do site.

Verifique:

- hero
- serviços
- avaliações Google
- FAQ
- formulário de pré-análise

Teste o envio do formulário.

---

# Segurança Recomendada

Após deploy em produção, recomenda-se:

- ativar HTTPS
- utilizar Cloudflare ou CDN
- instalar plugin de segurança
- configurar backup automático

Plugins recomendados:

Wordfence  
UpdraftPlus  

---

# Cache e Performance

Para melhorar o desempenho da landing page:

Plugins recomendados:

WP Fastest Cache  
LiteSpeed Cache  
WP Super Cache  

---

# Backup

Configure backups automáticos.

Recomendado:

UpdraftPlus

Backup mínimo:

- banco de dados
- uploads
- tema

---

# Atualizações

Antes de atualizar o tema:

1. faça backup completo
2. teste em ambiente de staging
3. valide compatibilidade com plugins

---

# Deploy com Git

Se utilizar Git para deploy:

Ignore arquivos desnecessários usando `.gitignore`.

Recomendado versionar apenas:

- tema
- documentação
- arquivos do projeto

Não versionar:

- uploads
- plugins
- núcleo do WordPress

---

# Deploy Concluído

Após esses passos o site estará pronto para produção.

A landing page estará configurada para:

- apresentar serviços
- captar leads
- exibir avaliações reais do Google
- responder dúvidas via FAQ