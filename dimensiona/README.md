# Dimensiona — loja + painel admin

Front-end completo em PHP, HTML, JS puro e **Tailwind CSS v4**, com duas versões:

| Pasta        | O que é                                             | Precisa de PHP? |
|--------------|-----------------------------------------------------|-----------------|
| raiz (`.php`)| Loja + painel ligados aos mesmos dados (`/data`)    | Sim (7.4+)      |
| `html/`      | Mesmas telas em HTML puro, para ver o layout        | Não             |

## Tailwind CSS

Os estilos são escritos com Tailwind CSS v4. Os arquivos-fonte ficam em `src/css/`:

| Fonte               | Gera                                                         |
|---------------------|--------------------------------------------------------------|
| `src/css/loja.css`  | `assets/css/style.css` e `html/assets/css/style.css`         |
| `src/css/admin.css` | `admin/assets/admin.css` e `html/admin/assets/admin.css`     |

Os `.css` gerados já vêm compilados no projeto, então a loja roda sem Node.
Para mudar o visual, edite **só** `src/css/` e recompile (precisa de Node.js 18+):

    npm install
    npm run build        # gera os 4 arquivos (minificados)
    npm run dev:loja     # recompila a loja a cada alteração (PHP)
    npm run dev:admin    # idem para o painel (rode em outro terminal)

Como usar no dia a dia:
- **Cores, fontes e breakpoints da marca** estão no bloco `@theme` e viram classes:
  `bg-surface`, `bg-surface-2`, `border-line`, `text-ink` (texto principal), `text-muted`,
  `text-faint`, `text-cyan`, `bg-blue`, `text-sale`, `font-serif`, `rounded-ui`, `bg-grad` (degradê).
  Breakpoints: `max-tablet:` (até 960px), `max-mobile:` (até 600px), `desk:` (a partir de 1100px)
  — no painel: `max-wide:` (1100px), `max-tablet:` (860px), `max-mobile:` (560px).
- **Componentes** (`.btn`, `.product-card`, `.panel`, `.a-btn`...) estão em `@layer components`,
  escritos com `@apply`. O PHP e o JavaScript continuam usando essas classes.
- **Ajustes pontuais** vão direto no HTML com utilitários, sem `style="..."`:
  `<div class="section__head mt-16">`. Utilitários sempre vencem os componentes.
- O Tailwind só gera os utilitários que encontra nos arquivos listados em `@source`
  (no topo de cada fonte). Se criar pasta nova com HTML/PHP/JS, adicione-a lá.

## Como rodar (versão PHP)

    php -S localhost:8000

- Loja: http://localhost:8000
- Painel: http://localhost:8000/admin/login.php
- Primeiro acesso: **admin@dimensiona.com.br** / **dimensiona2026**
  (no primeiro login a senha é convertida em hash e removida de `data/admin.json`)

A pasta `data/` precisa ter permissão de escrita para o PHP (ex.: `chmod 775 data`).
Para enviar fotos de produtos, `assets/img/produtos/` também precisa de escrita.

## O que o painel controla na loja

| No painel                        | Efeito na loja                                         |
|----------------------------------|--------------------------------------------------------|
| Produtos (criar/editar/excluir)  | Catálogo, destaques, novidades, promoções e busca      |
| Produto "Inativo"                | Some da loja sem ser apagado                           |
| Categorias                       | Filtros, cards e menu de categorias                    |
| Pedidos → status                 | Página de rastreio (`rastreio.php` usa `api/pedido.php`)|
| Configurações → frete e cupom    | Faixa do topo, carrinho (frete grátis, cupom, mínimo)  |
| Configurações → dados da loja    | Rodapé e contato                                       |
| Zona de risco → manutenção       | Visitantes veem aviso de manutenção (admin logado não)  |

Impressoras e estoque ficam salvos em `data/`. Os gráficos e indicadores de Visão Geral
e Clientes vêm de `data/painel.json` (troque por consultas reais quando tiver o back-end de vendas).

## Conteúdo de exemplo
- **Textos**: todo o conteúdo (títulos, descrições, depoimentos, nomes de produtos e categorias)
  está em lorem ipsum, no mesmo padrão do atalho `lorem` do VS Code (Emmet).
  Rótulos de interface (menu, botões, campos de formulário, links do rodapé) foram mantidos.
- **Imagens**: `assets/img/placeholder.svg` (produto, "IMAGEM DO PRODUTO") e
  `assets/img/placeholder-wide.svg` (imagens ilustrativas, "IMAGEM"). Troque pelas fotos reais
  no painel (Produtos > Enviar imagem) ou substituindo os arquivos.
- **Ícones**: sem emojis; os ícones do menu são SVG de linha, no próprio código.

## Estrutura

    index.php, sobre.php, ...     Páginas da loja
    includes/                     config.php (lê /data), header, footer, card de produto, manutenção
    api/pedido.php                Rastreio público (só status, sem dados do cliente)
    data/*.json                   Dados compartilhados (bloqueados por .htaccess)
    admin/
      login.php, logout.php
      index.php                   Visão Geral
      pedidos.php, produtos.php, categorias.php, impressoras.php,
      estoque.php, clientes.php, configuracoes.php
      api.php                     Salva as alterações (exige login + token CSRF)
      includes/                   bootstrap (sessão/auth), layout-top, layout-bottom
      assets/admin.css, admin.js  Estilos (gerado), gráficos em SVG, modais e CRUD
    src/css/                      Fontes Tailwind (loja.css, admin.css)
    package.json                  Scripts de build do Tailwind
    html/                         Versão estática da loja
    html/admin/                   Versão estática do painel (modo demonstração)

## Versão HTML (sem PHP)
Abra `html/index.html` ou `html/admin/login.html` direto no navegador.
No painel HTML qualquer e-mail/senha entra, e as alterações ficam salvas só no navegador
(localStorage) — elas não afetam a loja HTML, que é estática.

## Segurança (antes de publicar)
- Troque a senha padrão no primeiro acesso (basta fazer login uma vez).
- Em Nginx, bloqueie `/data` manualmente (o `.htaccess` só vale para Apache):
  `location ^~ /data/ { deny all; }`
- Use HTTPS: o cookie de sessão fica `secure` automaticamente.
