# 🚀 Checklist PageSpeed – Despachante Digital Flow (ATUALIZADO)

## 📊 Status Atual

Performance: ~75
Acessibilidade: ~84
Best Practices: 100
SEO: 100

---

# 🧠 PRIORIDADE 1 – HERO (ALTO IMPACTO)

## Status:

✔ Responsivo funcionando
❌ Ainda pode carregar imagem duplicada

## Próxima ação:

* Implementar `<picture>` para carregar apenas 1 imagem

---

# 🧠 PRIORIDADE 2 – CLS (CRÍTICO)

## Problema:

Layout shift ainda presente

## Ações:

* Definir altura fixa do hero
* Garantir width/height em imagens
* Evitar elementos pulando (FAQ, botões)

---

# 🧠 PRIORIDADE 3 – CSS

## Problema:

CSS bloqueante e não utilizado

## Ações:

* Reduzir Bootstrap (ou remover)
* Eliminar CSS não usado
* Evitar CSS inline no index.php

---

# 🧠 PRIORIDADE 4 – JS

## Status:

✔ TBT = 0 (ótimo)

## Ações:

* Remover dependências desnecessárias
* Garantir defer em todos scripts

---

# 🧠 PRIORIDADE 5 – IMAGENS

## Status:

✔ WebP ativo
✔ Lazy load ativo

## Ações:

* Garantir apenas 1 imagem carregada por breakpoint
* Validar tamanhos corretos (768x432 / 1600x900)

---

# 🧠 PRIORIDADE 6 – ACESSIBILIDADE

## Problemas:

* Inputs sem label
* Contraste baixo
* Hierarquia de headings

## Ações:

* Associar labels
* Ajustar cores
* Revisar h1 → h2 → h3

---

# 🧪 PROCESSO

✔ Fazer 1 ajuste
✔ Testar no PageSpeed
✔ Commit
✔ Nova branch

---

# 🎯 META

Performance: 90+
Acessibilidade: 90+
CLS: < 0.1
LCP: < 2.5s

---

# 🚀 PRÓXIMO PASSO

👉 Implementar `<picture>` na hero
(ganho mais rápido de performance agora)
