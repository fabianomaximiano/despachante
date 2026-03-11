# Guia de Instalação

## 1 Instalar WordPress

Instale WordPress normalmente ou via Docker.

---

## 2 Instalar o tema

Copie a pasta do projeto para:

wp-content/themes/despachante-theme

Depois ative o tema em:

Aparência → Temas

---

## 3 Instalar plugins necessários

Plugins obrigatórios:

WP Google Review Slider  
WP Mail SMTP

Links:

https://wordpress.org/plugins/wp-google-review-slider/  
https://wordpress.org/plugins/wp-mail-smtp/

Após instalar o plugin de avaliações:

Google Reviews → Create Review Slider

Após instalar o plugin SMTP:

WP Mail SMTP → Settings

Configure o envio de e-mails do WordPress.

---

## 4 Configurar envio de e-mails

O sistema de leads utiliza o WordPress para disparar notificações por e-mail.

Para isso, configure o plugin WP Mail SMTP.

Exemplo com Gmail:

- SMTP Host: smtp.gmail.com
- Porta: 587
- Criptografia: TLS
- Usuário: seuemail@gmail.com
- Senha: senha de aplicativo

Depois faça o envio de teste no plugin.

---

## 5 Cadastrar serviços

No painel:

Serviços → Adicionar novo

Informe:

Título  
Descrição  
Ícone FontAwesome  
Checklist de documentos

Exemplo:

fas fa-car  
fas fa-exchange-alt  

---

## 6 Cadastrar FAQ

No painel:

FAQ → Adicionar novo

---

## 7 Configurar personalização

Acesse:

Aparência → Personalizar

Configure:

cores  
estilo dos cards  
cores da seção de avaliações  
e-mail do escritório  

---

## 8 Inserir avaliações do Google

Crie o slider no plugin.

Depois confirme que o shortcode gerado está ativo.

A landing page exibirá automaticamente.

---

## 9 Testar formulário

Teste o envio do formulário com:

- nome
- WhatsApp
- e-mail
- serviço
- mensagem
- documentos

Confirme:

- validação dos campos
- salvamento do lead
- salvamento dos arquivos
- recebimento do e-mail

---

## Estrutura da página

Hero  
Serviços  
Avaliações Google  
Formulário  
FAQ  
Footer

---

## Pronto

Agora sua landing page de despachante está funcionando.