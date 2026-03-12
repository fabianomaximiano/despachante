# Checklist do Projeto
Tema: Despachante Digital Flow

Este documento acompanha o progresso de desenvolvimento da landing page para despachantes.

Use as caixas de seleção para marcar os itens concluídos.

---

# Estrutura Base do Tema

- [x] Estrutura inicial do tema WordPress
- [x] Arquivos principais criados
- [x] Organização de assets (css e js)
- [x] Integração Bootstrap
- [x] Integração FontAwesome

---

# Hero Section

- [x] Hero exibido na landing
- [x] Imagem de fundo configurável no painel
- [x] Título configurável
- [x] Subtítulo configurável
- [x] Texto do botão configurável
- [x] Link do botão configurável

---

# Serviços

- [x] CPT de serviços criado
- [x] Serviços exibidos na landing
- [x] Campo de ícone configurável
- [x] Lista de ícones sugeridos
- [x] Formato do ícone configurável (círculo ou quadrado)
- [x] Cor do ícone configurável
- [x] Cor de fundo configurável
- [x] Cor da borda configurável
- [x] Hover configurável
- [x] Layout de cards ajustado

---

# FAQ

- [x] CPT FAQ criado
- [x] Exibição em accordion
- [x] Conteúdo dinâmico via painel

---

# Avaliações Google

- [x] Seção criada na landing
- [x] Integração com plugin WP Google Review Slider
- [x] Campo para shortcode no painel
- [x] Aviso quando plugin não estiver instalado
- [x] Opções visuais no painel
- [x] Estrutura condicional no index.php

---

# Rodapé

- [x] Footer básico implementado
- [x] CTA no rodapé
- [x] Cor do fundo configurável
- [x] Campo de endereço completo
- [x] Campo de CEP
- [x] Campo de cidade / estado
- [x] Campo de telefone
- [x] Campo de WhatsApp do rodapé
- [x] Campo de e-mail
- [x] Campo de horário de atendimento
- [x] Exibir somente campos preenchidos
- [x] Correção visual do fundo do rodapé

---

# Redes Sociais

- [x] Campo LinkedIn
- [x] Campo Instagram
- [x] Campo Facebook
- [x] Campo X (Twitter)
- [x] Mostrar ícone apenas se preenchido
- [x] Link direto para rede social
- [x] Estilo "somente ícone"
- [x] Estilo "ícone dentro de círculo"
- [x] Estilo "ícone dentro de quadrado arredondado"

---

# WhatsApp

- [x] Botão flutuante fixo
- [x] Número configurável no painel
- [x] Mensagem padrão configurável
- [x] Texto do botão configurável
- [x] Checkbox para habilitar/desabilitar botão flutuante
- [x] Separação entre WhatsApp do desenvolvedor e WhatsApp do escritório
- [x] Checkbox para habilitar/desabilitar botão "Falar com o Desenvolvedor"

---

# Formulário de Pré-Análise

- [x] Layout do formulário criado
- [x] Campo nome
- [x] Campo WhatsApp
- [x] Campo e-mail
- [x] Campo seleção de serviço
- [x] Campo objetivo do atendimento
- [x] Campo mensagem
- [x] Campo de upload de documentos
- [x] Campo de documentos extras
- [x] Campo de consentimento LGPD

---

# Funcionalidades do Formulário

## Formulário

- [x] Processamento real do envio
- [x] Validação de campos no frontend
- [x] Validação de campos no backend
- [x] Validação de WhatsApp
- [x] Validação de e-mail
- [x] Proteção nonce
- [x] Mensagem de sucesso
- [x] Mensagem de erro
- [x] Exibição de erros por campo
- [x] Gravação segura dos dados enviados

---

## Checklist de Documentos

- [x] Criar checklist de documentos
- [x] Upload separado por documento
- [x] Mostrar documentos necessários por serviço
- [x] Interface de checklist amigável
- [x] Checklist configurável por serviço no painel
- [x] Validação de checklist obrigatório

Exemplo de documentos:

- RG
- CPF
- CNH
- comprovante de residência
- CRLV
- comprovante de pagamento
- procuração
- outros documentos

---

## Upload de Arquivos

- [x] Upload funcional no servidor
- [x] Validação de tipo de arquivo
- [x] Limite de tamanho
- [x] Salvamento em diretório organizado
- [x] Geração de links para download dos arquivos
- [x] Correção de URL pública para ambiente local/Docker

---

## Banco de Dados / Leads

- [x] Criar sistema de leads
- [x] Criar tabela de leads
- [x] Criar tabela de arquivos enviados
- [x] Salvar dados do formulário
- [x] Salvar serviço solicitado
- [x] Salvar objetivo do atendimento
- [x] Salvar links dos arquivos
- [x] Salvar data do envio
- [x] Salvar consentimento LGPD
- [x] Salvar IP
- [x] Salvar user agent
- [x] Controle de versão do banco

---

## Painel Administrativo de Leads

- [x] Criar área administrativa de leads
- [x] Visualizar dados enviados
- [x] Visualizar checklist esperado
- [x] Visualizar arquivos enviados
- [x] Baixar documentos enviados
- [x] Manter layout atual do painel
- [ ] Filtrar solicitações

---

## Envio de Email

- [x] Enviar dados do cliente por e-mail
- [x] Enviar serviço solicitado
- [x] Enviar objetivo do atendimento
- [x] Enviar mensagem do cliente
- [x] Enviar documentos anexados ou links
- [x] Enviar link do painel administrativo
- [x] Configurar e-mail do despachante
- [x] Template HTML de e-mail
- [x] Assunto dinâmico por tipo de atendimento
- [x] Envio de confirmação para o cliente
- [x] Identidade visual de e-mail via painel
- [x] Logo no e-mail
- [x] Nome da empresa no e-mail
- [x] Cor principal do e-mail
- [x] Texto de rodapé do e-mail

### Dependência recomendada

- [x] Instalar plugin SMTP no WordPress
- [x] Plugin recomendado: WP Mail SMTP

---

# LGPD

- [ ] Criar banner de LGPD
- [ ] Exibir no primeiro acesso
- [ ] Registrar consentimento
- [ ] Expiração de consentimento (15 dias)
- [x] Bloquear envio sem consentimento

---

# Formas de Pagamento

- [ ] Criar seção de pagamento
- [ ] Tornar seção opcional
- [ ] Opção habilitar/desabilitar no painel
- [ ] Campo Pix
- [ ] Campo cartão via Mercado Pago
- [ ] Campo dinheiro na hora
- [ ] Exibir somente métodos preenchidos

---

# Refatoração / Organização do Projeto

- [x] Reduzir tamanho do functions.php
- [x] Modularizar helpers.php
- [x] Modularizar setup.php
- [x] Modularizar database.php
- [x] Modularizar customizer.php
- [x] Modularizar cpts.php
- [x] Modularizar metaboxes.php
- [x] Modularizar uploads.php
- [x] Modularizar email.php
- [x] Modularizar form-handler.php
- [x] Modularizar admin-leads.php
- [x] Modularizar dynamic-css.php
- [x] Criar functions.php enxuto como loader dos módulos

---

# Documentação

- [x] README.md
- [x] INSTALL.md
- [x] INSTALACAO.md
- [x] CHANGELOG.md
- [x] DEPLOY.md
- [x] .gitignore
- [x] CHECKLIST.md
- [x] Atualizar documentação com WP Mail SMTP
- [x] Atualizar documentação do fluxo de e-mails
- [x] Atualizar checklist com estado real do projeto

---

# Melhorias Futuras

- [x] Melhorar UI do formulário
- [ ] Etapas no formulário (wizard)
- [ ] Painel de estatísticas de leads
- [ ] Integração com CRM
- [ ] Integração com API do Mercado Pago

---

# Melhoria Final Proposta

## Mini CRM de Leads

Objetivo:
Transformar o sistema de leads em uma estrutura mais completa de atendimento interno para o escritório.

### Ideias para a evolução final

- [ ] Adicionar status do lead
- [ ] Status: novo
- [ ] Status: em análise
- [ ] Status: aguardando documentos
- [ ] Status: processo iniciado
- [ ] Status: concluído
- [ ] Permitir atualização manual do status no painel
- [ ] Exibir histórico básico do andamento do lead
- [ ] Permitir observações internas do escritório
- [ ] Exportar leads em CSV

Observação:
Essa melhoria não é obrigatória para concluir o projeto atual, mas será a próxima evolução natural para transformar a landing page em um mini CRM de atendimento para despachantes.

---

# Status do Projeto

Versão atual: 3.3.7  
Estado: Estrutura principal concluída e funcional  
Próximo foco: acabamento final e validação completa do fluxo