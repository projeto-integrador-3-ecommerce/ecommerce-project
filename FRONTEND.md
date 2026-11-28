# Guia: refazendo o front com Tailwind (do jeito certo)

Este arquivo é o seu mapa. Leia de cima para baixo uma vez e depois use como consulta.

## 1. Em que estado o projeto está

O back-end e as blades estão **prontos e funcionando**. O visual foi **zerado de propósito**:

- As blades têm só HTML semântico + lógica (rotas, `@csrf`, `@can`, loops, validação, `old()`...). Nenhuma tem `class=`.
- `resources/css/app.css` é só a entrada do Tailwind (sem nenhum estilo seu ainda).
- `resources/js/app.js` tem os **comportamentos** (carrinho AJAX, busca, menu lateral, CEP, pagamento). Ele não sabe nada de visual.
- O front antigo (pasta `dimensiona/`, `storefront.css`, 4 blades sem uso) foi removido.

Sua missão: **escrever as classes do Tailwind direto nas blades**.

## 2. Rodando

```bash
npm install
composer run dev      # sobe servidor + vite juntos
# ou, em dois terminais:
php artisan serve
npm run dev
```

Se aparecer visual antigo ou erro de manifest, limpe os caches gerados:

```bash
php artisan view:clear
rm -rf public/build        # será recriado pelo npm run dev/build
```

## 3. Como o Tailwind funciona aqui (5 ideias)

1. **Utilitários, não CSS próprio.** Cada classe faz uma coisa: `p-4` (padding), `text-sm` (fonte), `bg-blue-600` (fundo). Você monta o visual combinando classes no HTML.
2. **O Tailwind lê seus arquivos.** Ele varre blades e JS procurando nomes de classe e gera só o CSS que você usou. Ele **ignora o que está no `.gitignore`** (por isso `app.css` libera `storage/framework/views` manualmente).
3. **Mobile-first.** Classe sem prefixo vale para tudo; prefixos *adicionam* para telas maiores: `grid-cols-1 md:grid-cols-3` = 1 coluna no celular, 3 a partir de `md` (48rem). Breakpoints: `sm` 40rem, `md` 48rem, `lg` 64rem, `xl` 80rem.
4. **Estado vira prefixo (variante).** `hover:`, `focus-visible:`, `disabled:`, `dark:`, `md:`... e podem ser combinados: `md:hover:bg-blue-700`.
5. **Sua marca fica em `@theme`** (no `app.css`). O que você declara lá vira classe:

```css
@theme {
    --color-brand-500: #1f7bff;   /* => bg-brand-500, text-brand-500, border-brand-500 */
    --color-brand-900: #0a1030;
    --font-display: 'Newsreader', serif;   /* => font-display */
}
```

## 4. As regras do "jeito certo"

**Regra 1 — classes nas blades, não CSS customizado.**
O front antigo fazia o contrário (`.btn`, `.summary__row` em um CSS de 31 KB com `@apply`) e virou um labirinto. Evite `@apply` e nomes inventados.

**Regra 2 — repetição se resolve com componente Blade, não com `@apply`.**
Se você copiou o mesmo botão 10 vezes, crie `resources/views/components/ui/button.blade.php`:

```blade
@props(['variant' => 'primary'])

@php
    $variants = [
        'primary' => 'bg-brand-500 text-white hover:brightness-110',
        'outline' => 'border border-slate-300 text-slate-900 hover:border-slate-500',
    ];
@endphp

<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'rounded-lg px-5 py-3 text-sm font-semibold disabled:cursor-not-allowed disabled:opacity-50 '.$variants[$variant],
]) }}>
    {{ $slot }}
</button>
```

Uso: `<x-ui.button variant="outline" type="submit">Pagar</x-ui.button>`.
(O projeto já tem um exemplo de componente: `components/storefront/product-card.blade.php`.)

**Regra 3 — nunca monte nome de classe por pedaços.**

```blade
{{-- ERRADO: o Tailwind não "enxerga" o resultado final, a classe não é gerada --}}
<span class="bg-{{ $color }}-500">

{{-- CERTO: nomes completos, escolhidos por um mapa --}}
@php($colors = ['red' => 'bg-red-500', 'green' => 'bg-green-500'])
<span class="{{ $colors[$color] }}">
```

Para classes condicionais use a diretiva `@class`:

```blade
<a @class(['px-3 py-2', 'font-bold text-brand-500' => $active])>
```

**Regra 4 — estado por atributo, não por classe.**
O CSS antigo usava `is-active`, `is-visible`. Agora o estado está em atributos HTML e o Tailwind estiliza com variantes:

```html
<a href="..." class="text-slate-500 aria-[current=page]:font-semibold aria-[current=page]:text-brand-500">
<label class="rounded-lg border p-3 has-[:checked]:border-brand-500">   <!-- radio selecionado -->
<input class="border focus:border-brand-500 invalid:border-red-500">
```

**Regra 5 — o JS nunca depende de classes de estilo.**
Ele usa `data-*` e `id`. Assim você pode mudar qualquer classe sem quebrar nada (veja a seção 5).

## 5. O "contrato" com o JS (não remova estes atributos)

| Atributo / id | Onde | O que o JS faz |
|---|---|---|
| `#sidebar`, `[data-sidebar-open]`, `[data-sidebar-close]` | layout | Abre/fecha o menu lateral |
| **`body[data-menu-open]`** | (JS coloca) | Menu aberto. Estilize com `group/body` + `group-data-[menu-open]/body:...` |
| `[data-account-menu]` | layout (`<details>`) | Fecha ao clicar fora / `Esc` |
| **`#toast[data-visible]`** | layout | Mensagem rápida visível por ~3s: `data-[visible]:opacity-100` |
| `[data-cart-count]` | layout | Selo do carrinho (usa o atributo `hidden`) |
| `[data-cart-mutation]` | forms do carrinho | Envia por AJAX (sem recarregar) |
| `[data-cart-item]`, `-select`, `-quantity`, `-subtotal`, `-items-container`, `-summary`, `[data-empty-cart]` | `carts/index` | Atualiza itens, totais e estado vazio |
| `#select-all-cart-items`, `[data-selected-count]`, `[data-summary-count]`, `[data-selected-total]`, `[data-order-submit]` | `carts/index` | Seleção e total do carrinho |
| `[data-async-search]`, `#search-input`, `#conteudo`, `[data-search-term]` | busca | Busca sem recarregar a página (troca o conteúdo de `#conteudo`) |
| `#cep`, `#street`, `#neighborhood`, `#city`, `#state` | endereços | Preenche o endereço pelo CEP (ViaCEP) |
| `[data-payment]`, `[data-pay-panel]`, `[data-card-form]`, `[data-field]`, `[data-preview]`, `[data-pay-feedback]`, `[data-pix-*]` | `payment/create` | Troca de forma de pagamento, máscara/preview do cartão, QR Code Pix |
| `data-supabase-*`, `[data-image-preview]` | `products/_form`, `categories/_form` | Upload de imagem (`public/js/supabase.js`) |
| `[data-filter-category]` / `[data-product-category]` | — | Filtro de categoria no cliente. **Nenhuma blade usa hoje**; é só um gancho disponível |

Atenção: os elementos com atributo `hidden` (painéis de pagamento, selo do carrinho) são escondidos pelo próprio navegador/preflight. Se você adicionar `flex` ou `grid` no mesmo elemento, confira no navegador se ele continua escondido.

## 6. Coisas que antes eram "escondidas por CSS"

Sem estilo, estes elementos aparecem na tela. É esperado, é a sua vez de decidir:

- Link "Pular para o conteúdo" (`<a href="#conteudo">`): costuma ficar com `sr-only focus:not-sr-only`.
- `<label for="site-search">` do campo de busca: `sr-only`.
- Inputs de rádio do pagamento: podem ficar `sr-only` com o `<label>` fazendo o papel de botão (`has-[:checked]:...`).
- Menu lateral (`#sidebar`) e botão de overlay: começam visíveis; esconda fora da tela e mostre com `data-menu-open`.

## 7. Mini-aula: menu lateral em 3 passos

Esse é o mais "difícil" do projeto e ensina tudo (estado por atributo + grupo). No `layouts/storefront.blade.php`:

```html
<body class="group/body data-[menu-open]:overflow-hidden">

<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full bg-slate-900 text-white
              transition-transform duration-300
              group-data-[menu-open]/body:translate-x-0">

<button data-sidebar-close aria-label="Fechar menu"
        class="fixed inset-0 z-40 bg-black/60 opacity-0 pointer-events-none transition-opacity
               group-data-[menu-open]/body:opacity-100 group-data-[menu-open]/body:pointer-events-auto">
</button>
```

Leitura: `group/body` marca o `<body>` como "grupo"; `group-data-[menu-open]/body:translate-x-0` significa "quando o body tiver `data-menu-open`, aplique `translate-x-0` neste elemento". O JS só liga/desliga o atributo; todo o visual é seu.

## 8. Ordem sugerida de trabalho

1. **Tokens**: cores e fontes da marca em `@theme` (`app.css`). Fontes: adicione o `<link>` do Google Fonts no `<head>` do layout ou use `@fontsource`.
2. **Layout** `layouts/storefront.blade.php` (cabeçalho, menu, rodapé, toast). Todas as páginas herdam dele.
3. **Componentes**: `product-card` e um `ui/button`.
4. **Páginas públicas**: `storefront/home`, `products/index|show`, `storefront/search`, `categories/index`, `storefront/about|novelties`.
5. **Formulários**: `auth/*`, `addresses/*`, `profile/edit` (crie um `ui/input` para não repetir).
6. **Compra**: `carts/index`, `orders/*`, `payment/create`.
7. **Admin**: `admin/dashboard`, `products|categories|settings/_form`, `users/index`.

Dica: termine uma tela por vez no celular (DevTools, 375px) e depois suba para `md:` e `lg:`.

## 9. Checklist de revisão

- [ ] Nenhum `class="bg-{{ ... }}"` (regra 3)
- [ ] Nenhum `@apply` novo (a não ser que você saiba explicar por quê)
- [ ] Botões repetidos viraram componente
- [ ] Foco visível em links, botões e inputs (`focus-visible:`)
- [ ] Testado no celular e no desktop
- [ ] `php artisan test` continua verde
- [ ] `npm run build` roda sem erro

## 10. Referências

- Documentação: <https://tailwindcss.com/docs> (use a busca; ela responde "qual classe faz X?")
- Componentes Blade: <https://laravel.com/docs/blade#components>
