# 🚀 Checklist de Melhorias PageSpeed – Despachante Digital Flow

## 📊 Situação Atual

* Performance: ~74 (não melhorou após ajustes)
* FCP: ótimo (~0.4s)
* LCP: bom (~0.8s) ✅
* CLS: **alto (1.011)** ❌ (principal problema atual)
* TBT: 0ms ✅

👉 **Conclusão:**
O problema agora NÃO é velocidade — é **layout shift (CLS)** e **CSS/estrutura**

---

# 🧠 PRIORIDADE 1 – CORRIGIR CLS (CRÍTICO)

## 🔥 Problema:

CLS está **1.011 (péssimo)**

## 🎯 Causas prováveis:

* Hero sem altura fixa
* Imagens sem dimensão definida
* Fonts carregando tarde
* Elementos "pulando" na tela

## ✅ Ações:

### 1. Definir altura do HERO

```css
.hero-section--responsive-bg {
  min-height: 80vh;
}
```

OU melhor:

```css
.hero-section {
  height: 100vh;
}
```

---

### 2. Definir tamanho das imagens

TODAS imagens devem ter:

```html
<img width="XXX" height="XXX">
```

---

### 3. Evitar mudança de layout no FAQ

Adicionar:

```css
.collapse {
  will-change: max-height;
}
```

---

### 4. Garantir fontes estáveis

No CSS:

```css
font-display: swap;
```

---

# 🥇 PRIORIDADE 2 – HERO (IMPACTO DIRETO NO LCP)

## 🔥 Problema:

Hero ainda não está otimizado corretamente

## ✅ Ações:

### 1. NÃO usar background-image

Trocar por:

```html
<picture>
  <source srcset="hero-mobile.webp" media="(max-width: 768px)">
  <img src="hero-desktop.webp" width="1600" height="900" loading="eager" fetchpriority="high">
</picture>
```

---

### 2. Garantir peso da imagem

* Desktop: < 150kb
* Mobile: < 100kb

---

# 🥈 PRIORIDADE 3 – CSS BLOQUEANTE

## 🔥 Problema:

Muito CSS carregando antes da renderização

## ✅ Ações:

### 1. Manter preload (já feito)

OK ✔

---

### 2. Reduzir CSS não usado

👉 Seu CSS está grande (3.4KB citado)

* Remover:

  * estilos não usados
  * classes duplicadas
  * bootstrap não utilizado

---

# 🥉 PRIORIDADE 4 – JS (JÁ QUASE OK)

## Situação:

* TBT = 0ms ✅

## Melhorias restantes:

### 1. Remover jQuery (se possível)

Só se `handlePreAnalise.js` não depender

---

### 2. Garantir defer em todos scripts

Já implementado ✔

---

# 🏆 PRIORIDADE 5 – IMAGENS (MELHORIA GERAL)

## ✅ Ações:

### 1. Todas imagens com:

```html
loading="lazy"
decoding="async"
```

---

### 2. Usar WebP sempre

✔ já implementado no backend

---

### 3. Evitar imagens grandes no DOM

* thumbnails pequenas
* não carregar imagens invisíveis

---

# ⚡ PRIORIDADE 6 – WORDPRESS CLEANUP

## ✅ Ações:

### 1. Remover coisas desnecessárias

Já feito:

* emoji ✔
* wp_generator ✔

---

### 2. Remover Dashicons no front

✔ já feito

---

# 🧪 PRIORIDADE 7 – TESTE CORRETO

⚠️ IMPORTANTE:

Sempre testar:

* modo anônimo
* celular (mais importante)
* com cache limpo

---

# 📌 RESUMO EXECUTIVO

## ❌ O que está errado agora:

* CLS ALTÍSSIMO (principal problema)
* Hero ainda mal estruturado (background-image)
* CSS possivelmente interferindo no layout

## ✅ O que já está bom:

* JS leve
* LCP bom
* WebP implementado
* Lazy load aplicado

---

# 🚀 PRÓXIMO PASSO (RECOMENDADO)

1. Corrigir HERO (CRÍTICO)
2. Corrigir CLS
3. Reavaliar CSS

---

# 💬 IMPORTANTE

👉 Não tente otimizar tudo de uma vez
👉 Faça 1 mudança → teste → medir

---

# 🎯 META

* CLS: < 0.1
* Performance: 90+
* LCP: < 2.5s

---
# 🚀 Checklist de Melhorias PageSpeed – Despachante Digital Flow

## 📊 Situação Atual

* Performance: ~74 (não melhorou após ajustes)
* FCP: ótimo (~0.4s)
* LCP: bom (~0.8s) ✅
* CLS: **alto (1.011)** ❌ (principal problema atual)
* TBT: 0ms ✅

👉 **Conclusão:**
O problema agora NÃO é velocidade — é **layout shift (CLS)** e **CSS/estrutura**

---

# 🧠 PRIORIDADE 1 – CORRIGIR CLS (CRÍTICO)

## 🔥 Problema:

CLS está **1.011 (péssimo)**

## 🎯 Causas prováveis:

* Hero sem altura fixa
* Imagens sem dimensão definida
* Fonts carregando tarde
* Elementos "pulando" na tela

## ✅ Ações:

### 1. Definir altura do HERO

```css
.hero-section--responsive-bg {
  min-height: 80vh;
}
```

OU melhor:

```css
.hero-section {
  height: 100vh;
}
```

---

### 2. Definir tamanho das imagens

TODAS imagens devem ter:

```html
<img width="XXX" height="XXX">
```

---

### 3. Evitar mudança de layout no FAQ

Adicionar:

```css
.collapse {
  will-change: max-height;
}
```

---

### 4. Garantir fontes estáveis

No CSS:

```css
font-display: swap;
```

---

# 🥇 PRIORIDADE 2 – HERO (IMPACTO DIRETO NO LCP)

## 🔥 Problema:

Hero ainda não está otimizado corretamente

## ✅ Ações:

### 1. NÃO usar background-image

Trocar por:

```html
<picture>
  <source srcset="hero-mobile.webp" media="(max-width: 768px)">
  <img src="hero-desktop.webp" width="1600" height="900" loading="eager" fetchpriority="high">
</picture>
```

---

### 2. Garantir peso da imagem

* Desktop: < 150kb
* Mobile: < 100kb

---

# 🥈 PRIORIDADE 3 – CSS BLOQUEANTE

## 🔥 Problema:

Muito CSS carregando antes da renderização

## ✅ Ações:

### 1. Manter preload (já feito)

OK ✔

---

### 2. Reduzir CSS não usado

👉 Seu CSS está grande (3.4KB citado)

* Remover:

  * estilos não usados
  * classes duplicadas
  * bootstrap não utilizado

---

# 🥉 PRIORIDADE 4 – JS (JÁ QUASE OK)

## Situação:

* TBT = 0ms ✅

## Melhorias restantes:

### 1. Remover jQuery (se possível)

Só se `handlePreAnalise.js` não depender

---

### 2. Garantir defer em todos scripts

Já implementado ✔

---

# 🏆 PRIORIDADE 5 – IMAGENS (MELHORIA GERAL)

## ✅ Ações:

### 1. Todas imagens com:

```html
loading="lazy"
decoding="async"
```

---

### 2. Usar WebP sempre

✔ já implementado no backend

---

### 3. Evitar imagens grandes no DOM

* thumbnails pequenas
* não carregar imagens invisíveis

---

# ⚡ PRIORIDADE 6 – WORDPRESS CLEANUP

## ✅ Ações:

### 1. Remover coisas desnecessárias

Já feito:

* emoji ✔
* wp_generator ✔

---

### 2. Remover Dashicons no front

✔ já feito

---

# 🧪 PRIORIDADE 7 – TESTE CORRETO

⚠️ IMPORTANTE:

Sempre testar:

* modo anônimo
* celular (mais importante)
* com cache limpo

---

# 📌 RESUMO EXECUTIVO

## ❌ O que está errado agora:

* CLS ALTÍSSIMO (principal problema)
* Hero ainda mal estruturado (background-image)
* CSS possivelmente interferindo no layout

## ✅ O que já está bom:

* JS leve
* LCP bom
* WebP implementado
* Lazy load aplicado

---

# 🚀 PRÓXIMO PASSO (RECOMENDADO)

1. Corrigir HERO (CRÍTICO)
2. Corrigir CLS
3. Reavaliar CSS

---

# 💬 IMPORTANTE

👉 Não tente otimizar tudo de uma vez
👉 Faça 1 mudança → teste → medir

---

# 🎯 META

* CLS: < 0.1
* Performance: 90+
* LCP: < 2.5s

---
