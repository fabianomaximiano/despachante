# Despachante Digital Flow

Tema WordPress desenvolvido para **landing pages de despachantes de veículos**, com foco em geração de leads, apresentação de serviços e integração com avaliações do Google.

O projeto foi construído para permitir **rápida personalização por painel administrativo**, sem necessidade de alterar código.

---

# Objetivo do Projeto

Criar uma landing page profissional para empresas de:

- Despachante de veículos
- Regularização documental
- Transferência de veículos
- Licenciamento e multas
- Primeiro emplacamento
- Alterações de características
- Baixa de gravame

O tema foi pensado para ser **reutilizável em diferentes clientes**, permitindo adaptar cores, serviços e conteúdo diretamente pelo painel.

---

# Estrutura da Landing Page

A página principal segue a estrutura:

Hero  
Serviços  
Avaliações Google  
FAQ  
Formulário de Pré-Análise  
Footer  

---

# Funcionalidades

## Hero configurável

No painel:

Aparência → Personalizar → Hero

Pode configurar:

- título
- subtítulo
- imagem de fundo
- texto do botão
- link do botão

Exemplo:

Título  
Despachante Veicular com Atendimento Rápido

Subtítulo  
Transferência, licenciamento e regularização de documentos.

Botão  
Solicitar Atendimento

---

# Serviços gerenciáveis

Os serviços são cadastrados via **Custom Post Type**.

No painel:

Serviços → Adicionar novo

Cada serviço possui:

- título
- descrição
- ícone FontAwesome

Exemplo de serviços:

- Transferência de propriedade
- Primeiro emplacamento
- Licenciamento anual
- Comunicação de venda
- Baixa de gravame
- Segunda via de documentos

---

# Ícones de serviços

No editor de serviços existe um campo para ícone.

Exemplos:

fas fa-car-side  
fas fa-exchange-alt  
fas fa-calendar-check  
fas fa-unlock  
fas fa-copy  
fas fa-cogs  

A biblioteca utilizada é:

FontAwesome 5

---

# Customização visual dos serviços

No painel:

Aparência → Personalizar → Serviços

É possível alterar:

- formato do ícone
- efeito hover
- cor de fundo do ícone
- cor da borda
- cor do ícone
- borda do card

Formatos disponíveis:

Quadrado com cantos arredondados  
Círculo  

Efeitos disponíveis:

Elevação  
Glow  
Zoom no ícone  

---

# Avaliações do Google

O tema possui integração com avaliações reais do Google utilizando o plugin:

WP Google Review Slider

Plugin oficial:

https://wordpress.org/plugins/wp-google-review-slider/

---

## Como ativar avaliações

### 1 Instale o plugin

No painel:

Plugins → Adicionar novo

Pesquise por:

WP Google Review Slider

Instale e ative.

---

### 2 Configure o slider

No painel:

Google Reviews → Create Slider

Conecte sua conta do Google Business.

---

### 3 Copie o shortcode gerado

Exemplo:

[wp-review-slider id="1"]

---

### 4 Cole no painel do tema

Acesse:

Aparência → Personalizar → Avaliações Google

Campo:

Shortcode do plugin de avaliações

Cole o shortcode gerado pelo plugin.

---

# Customização das avaliações

Também é possível personalizar:

Aparência → Personalizar → Avaliações Google

Configurações disponíveis:

- fundo da seção
- borda do card
- raio da borda
- sombra
- padding
- fundo do card

---

# FAQ gerenciável

Perguntas frequentes são cadastradas via painel.

No painel:

FAQ → Adicionar novo

Cada item possui:

- pergunta
- resposta

Os itens são exibidos em formato **accordion**.

---

# Formulário de pré-análise

A landing possui formulário para coleta de leads.

Campos:

- nome
- WhatsApp
- serviço desejado
- upload de documentos

O envio é tratado via JavaScript:

assets/js/handlePreAnalise.js

---

# Estrutura do Tema

/wp-content/themes/despachante/

assets/
   css/
      estyle.css
   js/
      handlePreAnalise.js

header.php  
footer.php  
functions.php  
index.php  
style.css  
README.md  

---

# Requisitos

- WordPress 6+
- PHP 7.4+
- MySQL ou MariaDB
- FontAwesome
- Bootstrap 4.6

---

# Execução com Docker

Este projeto pode ser executado com Docker.

Exemplo de estrutura:

docker-compose.yml  
wordpress/  
themes/despachante  

Após subir o container:

http://localhost:8000

Ative o tema em:

Aparência → Temas

---

# Público alvo

Este tema foi criado para:

- despachantes veiculares
- consultorias automotivas
- empresas de regularização documental

---

# Licença

Projeto open source para uso comercial e personalização.

---

# Autor

Projeto desenvolvido para **landing pages de despachantes digitais**.