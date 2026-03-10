# Guia de Instalação

Este documento explica como instalar e configurar o tema **Despachante Digital Flow**.

O tema foi desenvolvido para criar **landing pages para despachantes de veículos**, permitindo configuração visual e gerenciamento de conteúdo diretamente pelo painel WordPress.

---

# Requisitos

Antes de instalar o tema, verifique se o ambiente possui:

- WordPress 6+
- PHP 7.4 ou superior
- MySQL ou MariaDB
- Navegador moderno
- Acesso ao painel administrativo do WordPress

---

# 1. Instalar WordPress

Instale o WordPress normalmente em seu servidor.

Exemplo de acesso após instalação:

http://localhost  
ou  
https://seudominio.com

---

# 2. Instalar o Tema

Copie a pasta do tema para o diretório:

wp-content/themes/

A estrutura deve ficar assim:

wp-content/themes/despachante/

Dentro da pasta devem existir arquivos como:

functions.php  
index.php  
header.php  
footer.php  
style.css  

---

# 3. Ativar o Tema

No painel do WordPress:

Aparência → Temas

Ative o tema:

Despachante Digital Flow

---

# 4. Instalar Plugin de Avaliações do Google

Para exibir avaliações do Google, instale o plugin:

WP Google Review Slider

Link oficial:

https://wordpress.org/plugins/wp-google-review-slider/

No painel:

Plugins → Adicionar novo

Pesquise por:

WP Google Review Slider

Instale e ative o plugin.

---

# 5. Criar Slider de Avaliações

Após ativar o plugin, configure o slider.

No painel:

Google Reviews → Create Slider

Conecte sua conta do Google Business.

Depois de criar o slider, copie o shortcode gerado.

Exemplo:

[wp-review-slider id="1"]

---

# 6. Inserir Shortcode no Tema

Acesse:

Aparência → Personalizar → Avaliações Google

No campo:

Shortcode do plugin de avaliações

Cole o shortcode gerado pelo plugin.

Exemplo:

[wp-review-slider id="1"]

---

# 7. Configurar Hero da Página

No painel:

Aparência → Personalizar → Hero

Configure:

- Título principal
- Subtítulo
- Imagem de fundo
- Texto do botão
- Link do botão

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

# 8. Cadastrar Serviços

No painel:

Serviços → Adicionar novo

Informe:

- título do serviço
- descrição
- ícone FontAwesome

Exemplo de ícones:

fas fa-car-side  
fas fa-exchange-alt  
fas fa-calendar-check  
fas fa-unlock  
fas fa-copy  
fas fa-cogs  

---

# 9. Cadastrar FAQ

No painel:

FAQ → Adicionar novo

Informe:

- pergunta
- resposta

Esses itens aparecerão automaticamente na seção de perguntas frequentes.

---

# 10. Testar Formulário

A landing possui formulário de pré-análise.

Campos:

- nome
- WhatsApp
- serviço desejado
- upload de documentos

O comportamento do formulário é controlado por:

assets/js/handlePreAnalise.js

Teste o envio diretamente na página inicial.

---

# 11. Personalizar Visual

No painel:

Aparência → Personalizar

É possível configurar:

Hero  
Serviços  
Avaliações Google  
Rodapé  

Isso permite adaptar o tema para diferentes clientes.

---

# Estrutura do Tema

/wp-content/themes/despachante/

assets/  
css/  
estyle.css  

js/  
handlePreAnalise.js  

functions.php  
header.php  
footer.php  
index.php  
style.css  

README.md  
CHANGELOG.md  
INSTALL.md  

---

# Pronto

Após seguir os passos acima, sua **landing page de despachante estará totalmente funcional**.