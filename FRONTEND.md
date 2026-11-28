# Guia: front-end Dimensiona (Tailwind v4)

Este arquivo é o mapa do front. Consulte a seção 5 antes de remover/renomear qualquer `id` ou `data-*`: o JS depende deles, não das classes.

## 1. Estado atual

O front foi refeito diretamente nas blades com classes utilitárias do Tailwind. Back-end, rotas, controllers, models, migrations, policies, tests e `config/` permanecem intactos.

- `resources/css/app.css`: entrada do Tailwind + `@theme` com os tokens da marca.
- `vite.config.js`: Inter 400/500/600/700 via `bunny` do `laravel-vite-plugin/fonts`.
- `resources/js/app.js`: comportamentos existentes + carrossel, filtro do carrinho e preview do cartão/Pix; nenhuma dependência de classe de estilo.
- `resources/views/components/ui/*`: componentes reutilizáveis (botão, link, input, select, textarea, badge, badge-new, logo, address-card).
- `resources/views/components/storefront/product-card.blade.php`: card usado no catálogo, busca, novidades e home; aceita `forceNewBadge` para manter o selo sempre visível em home/novelties.
- Todas as páginas públicas, auth, carrinho, pedidos, endereços, pagamento, perfil e admin estão estilizadas.

Não existe `.ai/rules/index.md` neste projeto.

## 2. Rodando e verificando

```bash
npm run build
php artisan test
```

Última verificação: `BUILD_EXIT=0`, `43 passed`, `148 assertions`. O build emite apenas um aviso não bloqueante do plugin de fontes sobre o pacote opcional `fontaine`; nenhum pacote foi instalado por causa disso.

Se aparecer erro de manifest, rode `npm run build` (ou `npm run dev`). Se aparecer visual antigo, rode `php artisan view:clear`.

## 3. Tokens e convenções

`@theme` em `resources/css/app.css`:

| Token | Valor | Uso principal |
|---|---|---|
| `--color-bg` | `#eefeff` | fundo da loja |
| `--color-navy` | `#010f37` | texto, botões, superfícies escuras |
| `--color-aqua` | `#b3e8ec` | bordas suaves, destaques |
| `--color-mist` | `#b3dadd` | superfícies secundárias |
| `--color-teal` | `#00a6b2` | accents, sucesso |
| `--color-blue` | `#0c8ce9` | foco, seleção, links ativos |
| `--color-red` | `#ff0000` | erros, selo do carrinho |
| `--color-muted` | `#858f90` | texto secundário |
| `--color-overlay` | `#939d9e` | overlay |
| `--font-sans` | Inter | tipografia padrão |

Regras seguidas:

- Classes utilitárias direto nas blades; zero `@apply` e zero `style=""`.
- Nenhum nome de classe montado por pedaços. Classes condicionais usam mapas completos (ex.: `$statusStyles[$order->status]`) ou `@class` (ex.: abas de login/registro), nunca `bg-{{ $cor }}`.
- Componentes Blade com `@props` + `$attributes->merge()` para repetição.
- Estado por atributo: `has-[:checked]`, `aria-[current=page]`, `data-[menu-open]`, `data-[visible]`, `aria-[pressed=true]`.
- Responsivo mobile-first: `sm:`, `md:`, `lg:`; foco visível com `focus-visible:ring-2`.
- Nenhuma biblioteca de UI, CDN novo ou dependência nova. Exceção preservada: o script do Supabase em `products/_form` e `categories/_form` via `@pushOnce('scripts')` já existia e é necessário para o upload.

## 4. Componentes `ui/*`

| Componente | Props | Observações |
|---|---|---|
| `x-ui.button` | `variant` (`primary`, `outline`, `light`, `ghost`, `danger`), `size` (`sm`, `md`, `lg`) | `type="button"` por padrão; use `type="submit"` quando necessário. |
| `x-ui.link` | mesmas variantes/sizes de button | Já inclui estilo para `aria-current="page"`. |
| `x-ui.input` | `label`, `hint` | Repassa `name`, `id`, `value`, `maxlength`, `data-*`, etc. |
| `x-ui.select` | `label`, `hint` | Envolve `<select>` com seta decorativa; opções vão no slot. |
| `x-ui.textarea` | `label`, `hint` | `rows="4"` por padrão; conteúdo vai no slot. |
| `x-ui.badge` | `variant` | Usado em status e chips. |
| `x-ui.badge-new` | `label` (padrão `Novo`) | Pílula vermelha `#ff0000` com `^` branco decorativo e texto acessível via `sr-only`. |
| `x-ui.logo` | `size` (`sm`, `md`, `lg`) | SVG em `public/images/dimensiona/logo.svg`. |
| `x-ui.address-card` | `title`, `$address`, `editRoute`, `editLabel` | Usado em pedido/pagamento; aceita endereço nulo. |

`x-ui.button` e `x-ui.link` recebem `class` extra via `$attributes->merge`, então `class="mt-6 w-full"` funciona normalmente.

## 5. Contrato com o JS (não remover)

| Atributo / id | Onde | O que o JS faz |
|---|---|---|
| `#conteudo`, `[data-async-search]`, `#search-input`, `[data-search-term]`, `data-term` | layout, busca | Busca assíncrona; troca o `#conteudo` e foca o input. Termos populares preenchem o campo. |
| `#sidebar`, `[data-sidebar-open]`, `[data-sidebar-close]`, `body[data-menu-open]` | layout | Abre/fecha o dropdown branco ancorado em “Categorias”; `inert`, `aria-hidden`, `aria-expanded`, foco no primeiro link, retorno ao gatilho e overlay transparente de clique fora são gerenciados pelo JS. |
| `[data-account-menu]` | layout (`<details>`) | Fecha ao clicar fora ou `Esc`. |
| `#toast[data-visible]` | layout | Mensagem por ~2,8s. |
| `[data-cart-count]` | layout | Atualiza o selo do carrinho e usa `hidden`. |
| `[data-cart-mutation]` | product-card, products/show, carts/index | Envia add/remove ao carrinho via AJAX. |
| `[data-cart-item]`, `data-quantity`, `[data-cart-select]`, `[data-cart-quantity]`, `[data-cart-subtotal]`, `[data-cart-items-container]`, `[data-empty-cart]` | carts/index | Atualiza quantidade, subtotal e estado vazio. O container tem `data-catalog-url` para o link vazio gerado pelo JS. |
| `#select-all-cart-items`, `[data-selected-count]`, `[data-summary-count]`, `[data-selected-total]`, `[data-summary-estimated]`, `[data-order-submit]`, `[data-cart-filter]`, `form#order-selection` | carts/index | Seleção, filtro "Tudo/Selecionado", resumo e botão `Finalizar compra (N)`. |
| `#cep`, `#street`, `#neighborhood`, `#city`, `#state` | addresses/create, addresses/edit | Preenche o endereço via ViaCEP no `blur`. |
| `[data-payment]`, `[data-pay-panel]`, `[data-card-form]`, `[data-field]`, `[data-preview]`, `[data-pay-feedback]`, `[data-pix-result]`, `[data-pix-qr]`, `data-seed`, `[data-pix-generate]`, `[data-pix-copy]`, `[data-pix-code]`, `input[name="payment_method"]` | payment/create | Troca de painel, máscara/prévia do cartão, QR ilustrativo e copia e cola. |
| `[data-supabase-image-upload]`, `data-upload-url`, `data-supabase-url`, `data-supabase-bucket`, `[data-supabase-folder]`, `[data-image-preview]`, `[data-supabase-upload-status]` | products/_form, categories/_form | Upload via `public/js/supabase.js`. |
| `[data-carousel]`, `[data-carousel-track]`, `[data-carousel-slide]`, `[data-carousel-prev]`, `[data-carousel-next]`, `[data-carousel-dot]`, `data-carousel-index` | storefront/home | Carrossel do hero. |
| `[data-filter-category]` / `[data-product-category]` | disponível; `data-product-category` está no product-card | Filtro por categoria; nenhum botão `[data-filter-category]` está em uso hoje. |

Elementos com `hidden` (painéis de pagamento, selo do carrinho, resumo vazio) não devem receber `flex`/`grid` no mesmo elemento sem conferir no navegador: o atributo `hidden` do HTML tem prioridade.

## 6. Decisões e adaptações

- O modelo `Product` não tem preço promocional: o card usa `old_price`/`compare_at_price` quando presente e cai para o preço normal.
- Não há regra de frete grátis em `Setting/config`: o aviso usa `config('storefront.free_shipping_threshold')` e o texto "O frete será calculado ao finalizar o pedido".
- Não há valor de promoção no back-end: a faixa usa `config('storefront.promo_value', 'R$ 10')` e `config('storefront.promo_minimum', 'R$ 199,00')`.
- O selo vermelho do carrinho continua em `bg-red`; o selo “Novo” dos cards é `x-ui.badge-new`, sempre visível em home/novelties e com regra de 14 dias nas demais listagens.
- `#sidebar` é um `<nav aria-label="Categorias">` branco, ancorado com `absolute top-full left-0 mt-2` dentro de um wrapper `relative`; `w-72`, `max-w-[calc(100vw-2rem)]`, `max-h-[60vh]`, borda `aqua`, sombra longa, fade + `-translate-y-1` e `motion-reduce:transition-none`. Não há `role="dialog"`, `aria-modal`, título interno, botão X nem overlay escuro: o fechamento fora usa um botão `fixed inset-0 bg-transparent` com `[data-sidebar-close]`, atrás do painel. O chevron do gatilho gira 180° e a seção “Loja” só aparece no dropdown em `md:hidden`.
- Nenhum link público aponta para `categories.index`; a rota continua pública em `routes/categories.php`, sem middleware de admin, e só as blades administrativas `categories/create`, `categories/edit` e `categories/_form` linkam de volta para ela.
- Home termina em “Produtos em destaque”; FDM/SLA/MSLA, “Como funciona”, “Explore a loja”, “Do projeto à peça pronta”, tabela de tecnologias e CTA final foram removidos também de layout, categories e about. A `CategoryControllerTest` continua lendo a URL da imagem da primeira categoria na home via `<img class="hidden">`, sem bloco visual de categorias.
- Cartão `Conta / Pedidos / Horário` em `storefront/about` usa `grid items-start`, mesma estrutura nas 3 colunas, links `px-0 py-2`, `min-h-11` e `hover:underline`.
- SVGs inline revisitados: ícones do header/promoção/benefícios/carrinho/pagamento e logos sociais usam paths Lucide/Heroicons (MIT) ou Simple Icons (CC0), com `viewBox`, tamanho por classe, `fill`/`stroke` explícitos e `aria-hidden` quando decorativos. Google usa `#4285F4/#34A853/#FBBC05/#EA4335`, Facebook `#1877F2` e Apple `#010f37`.
- Imagens ilustrativas usam `public/images/dimensiona/placeholder.svg`.
- Pix e cartão são simulações visuais: nenhum dado é enviado; o QR é gerado no cliente e não é cobrável.
- `docs/design/*.png` não pôde ser inspecionado neste ambiente; o visual seguiu a descrição textual do pedido.

## 7. Checklist final

- [x] Nenhum `class="bg-{{ ... }}"` ou classe montada por pedaços.
- [x] Nenhum `@apply` novo e nenhum `style=""` nas blades.
- [x] Botões/links/inputs repetidos viraram componentes.
- [x] Foco visível em links, botões e inputs.
- [x] Páginas responsivas em `sm:`, `md:` e `lg:`.
- [x] `php artisan test` verde (43/43).
- [x] `npm run build` sem erro (`BUILD_EXIT=0`).
- [x] Grep limpo para “Como funciona”, “FDM”, “MSLA”, “Do projeto à peça pronta” e “Explore a loja” nas blades.
- [x] Grep limpo para `@apply`, `style=""` e classes montadas por pedaços.
- [x] Hooks antigos preservados e novos hooks documentados acima.
- [x] Grep de `categories.index` nas blades só encontra links administrativos; nenhum link público restante.
- [x] Dropdown de categorias sem `role="dialog"`, sem overlay escuro e com hooks `#sidebar`/`data-sidebar-*` preservados.

## 8. Referências

- Documentação do Tailwind: <https://tailwindcss.com/docs>
- Componentes Blade: <https://laravel.com/docs/blade#components>
