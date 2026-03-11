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

# Documentação

- [x] README.md
- [x] INSTALL.md
- [x] CHANGELOG.md
- [x] DEPLOY.md
- [x] .gitignore
- [x] CHECKLIST.md

---

# Formulário de Pré-Análise

- [x] Layout do formulário criado
- [x] Campo nome
- [x] Campo WhatsApp
- [x] Campo seleção de serviço
- [x] Campo de upload de documentos (visual)

---

# Funcionalidades do Formulário

## Formulário

- [x] Processamento real do envio
- [x] Validação de campos
- [x] Proteção nonce
- [x] Mensagem de sucesso
- [x] Mensagem de erro
- [x] Gravação segura dos dados enviados

---

## Checklist de Documentos

- [x] Criar checklist de documentos
- [x] Upload separado por documento
- [x] Mostrar documentos necessários por serviço
- [x] Interface de checklist amigável

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

---

## Banco de Dados / Leads

- [x] Criar sistema de leads
- [x] Salvar dados do formulário
- [x] Salvar serviço solicitado
- [x] Salvar links dos arquivos
- [x] Salvar data do envio
- [x] Salvar consentimento LGPD

---

## Painel Administrativo de Leads

- [x] Criar CPT ou tabela para solicitações
- [x] Visualizar dados enviados
- [x] Baixar documentos enviados
- [ ] Filtrar solicitações

---

## Envio de Email

- [x] Enviar dados do cliente por email
- [x] Enviar serviço solicitado
- [x] Enviar documentos anexados ou links
- [x] Configurar email do despachante

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

# Melhorias Futuras

- [x] Melhorar UI do formulário
- [ ] Etapas no formulário (wizard)
- [ ] Painel de estatísticas de leads
- [ ] Integração com CRM
- [ ] Integração com API do Mercado Pago

---

# Status do Projeto

Versão atual: 3.3.7  
Estado: Em desenvolvimento avançado