/* =========================================================
   Dimensiona — scripts globais
   ========================================================= */
(function () {
    'use strict';

    const CART_KEY = 'dimensiona_cart';
    const SIDEBAR_KEY = 'dimensiona_sidebar';
    // Valores vêm do <body> (definidos em Admin > Configurações). Os números abaixo são o padrão da versão HTML.
    const cfg = document.body.dataset;
    const FREE_SHIPPING_MIN = Number(cfg.freeShipping || 299);
    const SHIPPING_FEE = Number(cfg.shippingFee || 24.9);
    const COUPON = {
        code: (cfg.coupon ?? 'DIMENSIONA10').toUpperCase(),
        discount: Number(cfg.couponDiscount || 10) / 100,
        min: Number(cfg.couponMin || 0)
    };
    const API = cfg.api || null; // só existe na versão PHP

    const $  = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
    const money = (v) => v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

    /* ---------- Armazenamento seguro ---------- */
    const store = {
        get(key, fallback) {
            try { const v = localStorage.getItem(key); return v ? JSON.parse(v) : fallback; }
            catch (_) { return fallback; }
        },
        set(key, value) {
            try { localStorage.setItem(key, JSON.stringify(value)); } catch (_) { /* sem armazenamento */ }
        }
    };

    /* ---------- Toast ---------- */
    let toastTimer;
    function toast(message) {
        const el = $('#toast');
        if (!el) return;
        el.textContent = message;
        el.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => el.classList.remove('is-visible'), 2400);
    }

    /* ---------- Menu lateral ---------- */
    function initSidebar() {
        const body = document.body;
        const openBtn = $('.js-sidebar-open');
        const isDesktop = () => window.matchMedia('(min-width: 1100px)').matches;

        const setOpen = (open, persist = true) => {
            body.classList.toggle('sidebar-open', open);
            if (openBtn) openBtn.setAttribute('aria-expanded', String(open));
            if (persist && isDesktop()) store.set(SIDEBAR_KEY, open);
        };

        // No desktop o menu começa aberto (como no layout); no celular começa fechado.
        setOpen(isDesktop() ? store.get(SIDEBAR_KEY, true) : false, false);

        $$('.js-sidebar-open').forEach(b => b.addEventListener('click', () => setOpen(true)));
        $$('.js-sidebar-close').forEach(b => b.addEventListener('click', () => setOpen(false)));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && body.classList.contains('sidebar-open') && !isDesktop()) setOpen(false);
        });
    }

    /* ---------- Carrinho ---------- */
    const cart = {
        items() { return store.get(CART_KEY, []); },
        save(items) { store.set(CART_KEY, items); updateCartCount(); },
        add(product) {
            const items = this.items();
            const found = items.find(i => i.id === product.id);
            if (found) found.qty += 1; else items.push({ ...product, qty: 1 });
            this.save(items);
        },
        setQty(id, qty) {
            let items = this.items();
            items = qty <= 0 ? items.filter(i => i.id !== id) : items.map(i => i.id === id ? { ...i, qty } : i);
            this.save(items);
        },
        remove(id) { this.save(this.items().filter(i => i.id !== id)); },
        count() { return this.items().reduce((n, i) => n + i.qty, 0); },
        subtotal() { return this.items().reduce((s, i) => s + i.price * i.qty, 0); }
    };

    function updateCartCount() {
        const n = cart.count();
        $$('.js-cart-count').forEach(el => { el.textContent = n; el.hidden = n === 0; });
    }

    function initAddToCart() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.js-add-to-cart');
            if (!btn) return;
            cart.add({
                id: Number(btn.dataset.id),
                name: btn.dataset.name,
                price: Number(btn.dataset.price),
                img: btn.dataset.img
            });
            const original = btn.dataset.label || btn.textContent.trim();
            btn.dataset.label = original;
            btn.textContent = 'Adicionado ✓';
            btn.classList.add('is-added');
            setTimeout(() => { btn.textContent = original; btn.classList.remove('is-added'); }, 1600);
            toast(`${btn.dataset.name} foi adicionado ao carrinho`);
        });
    }

    /* ---------- Página do carrinho ---------- */
    function initCartPage() {
        const root = $('#cart-root');
        if (!root) return;
        let couponApplied = store.get('dimensiona_coupon', false);

        const render = () => {
            const items = cart.items();
            const empty = $('#cart-empty');
            const filled = $('#cart-filled');
            empty.hidden = items.length > 0;
            filled.hidden = items.length === 0;
            if (!items.length) return;

            $('#cart-list').innerHTML = items.map(i => `
                <article class="cart-item" data-id="${i.id}">
                    <img src="${i.img}" alt="">
                    <div>
                        <p class="cart-item__name">${escapeHtml(i.name)}</p>
                        <p class="cart-item__price">${money(i.price)}</p>
                    </div>
                    <div class="cart-item__actions">
                        <div class="qty" role="group" aria-label="Quantidade de ${escapeHtml(i.name)}">
                            <button type="button" data-act="dec" aria-label="Diminuir">−</button>
                            <span>${i.qty}</span>
                            <button type="button" data-act="inc" aria-label="Aumentar">+</button>
                        </div>
                        <button type="button" class="link-btn" data-act="remove">Remover</button>
                    </div>
                </article>`).join('');

            const subtotal = cart.subtotal();
            if (couponApplied && (!COUPON.code || subtotal < COUPON.min)) couponApplied = false;
            const discount = couponApplied ? subtotal * COUPON.discount : 0;
            const base = subtotal - discount;
            const shipping = base >= FREE_SHIPPING_MIN ? 0 : SHIPPING_FEE;
            const remaining = Math.max(0, FREE_SHIPPING_MIN - base);

            $('#sum-subtotal').textContent = money(subtotal);
            $('#sum-discount-row').hidden = !couponApplied;
            $('#sum-discount').textContent = '− ' + money(discount);
            $('#sum-shipping').textContent = shipping === 0 ? 'Grátis' : money(shipping);
            $('#sum-total').textContent = money(base + shipping);
            $('#sum-ship-msg').textContent = remaining > 0
                ? `Faltam ${money(remaining)} para ganhar frete grátis`
                : 'Você ganhou frete grátis';
            $('#sum-progress').style.width = Math.min(100, (base / FREE_SHIPPING_MIN) * 100) + '%';
        };

        $('#cart-list').addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-act]');
            if (!btn) return;
            const id = Number(btn.closest('.cart-item').dataset.id);
            const item = cart.items().find(i => i.id === id);
            if (!item) return;
            if (btn.dataset.act === 'inc') cart.setQty(id, item.qty + 1);
            if (btn.dataset.act === 'dec') cart.setQty(id, item.qty - 1);
            if (btn.dataset.act === 'remove') { cart.remove(id); toast('Item removido do carrinho'); }
            render();
        });

        const couponForm = $('#coupon-form');
        couponForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = $('#coupon-input');
            const err = $('#coupon-error');
            if (COUPON.code && input.value.trim().toUpperCase() === COUPON.code) {
                if (cart.subtotal() < COUPON.min) {
                    err.textContent = `Este cupom vale para pedidos a partir de ${money(COUPON.min)}.`;
                    input.classList.add('is-invalid');
                    return;
                }
                couponApplied = true;
                store.set('dimensiona_coupon', true);
                err.textContent = '';
                input.classList.remove('is-invalid');
                toast(`Cupom aplicado: ${Math.round(COUPON.discount * 100)}% de desconto`);
            } else {
                err.textContent = 'Cupom não encontrado. Confira o código e tente de novo.';
                input.classList.add('is-invalid');
            }
            render();
        });

        $('#checkout-btn').addEventListener('click', () => {
            toast('Checkout disponível quando o back-end de pagamentos for integrado');
        });

        render();
    }

    /* ---------- Filtros de categoria (categorias.php) ---------- */
    function initCategoryFilter() {
        const bar = $('#category-filter');
        if (!bar) return;
        const cards = $$('#catalog-grid .product-card');
        const counter = $('#catalog-count');

        const apply = (cat) => {
            let visible = 0;
            cards.forEach(c => {
                const show = cat === 'todos' || c.dataset.category === cat;
                c.hidden = !show;
                if (show) visible++;
            });
            $$('.chip', bar).forEach(ch => {
                const active = ch.dataset.cat === cat;
                ch.classList.toggle('is-active', active);
                ch.setAttribute('aria-pressed', String(active));
            });
            if (counter) counter.textContent = visible + (visible === 1 ? ' produto' : ' produtos');
            const url = new URL(window.location);
            if (cat === 'todos') url.searchParams.delete('cat'); else url.searchParams.set('cat', cat);
            history.replaceState(null, '', url);
        };

        bar.addEventListener('click', (e) => {
            const chip = e.target.closest('.chip');
            if (chip) apply(chip.dataset.cat);
        });
        $$('.js-cat-link').forEach(a => a.addEventListener('click', (e) => {
            e.preventDefault();
            apply(a.dataset.cat);
            $('#todos-os-produtos').scrollIntoView({ behavior: 'smooth' });
        }));

        const fromUrl = new URLSearchParams(location.search).get('cat');
        const valid = $$('.chip', bar).some(c => c.dataset.cat === fromUrl);
        apply(valid ? fromUrl : (bar.dataset.initial || 'todos'));
    }

    /* ---------- Busca ao vivo (pesquisar.php) ---------- */
    function initSearch() {
        const input = $('#search-input');
        if (!input) return;
        const cards = $$('#search-grid .product-card');
        const empty = $('#search-empty');
        const normalize = (s) => s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

        const run = () => {
            const q = normalize(input.value.trim());
            const words = q.split(/\s+/).filter(Boolean);
            let visible = 0;
            cards.forEach(c => {
                const haystack = normalize(c.dataset.name);
                const show = !words.length || words.every(w => haystack.includes(w));
                c.hidden = !show;
                if (show) visible++;
            });
            empty.hidden = visible > 0;
            const url = new URL(window.location);
            if (q) url.searchParams.set('q', input.value.trim()); else url.searchParams.delete('q');
            history.replaceState(null, '', url);
        };

        const qFromUrl = new URLSearchParams(location.search).get('q');
        if (qFromUrl && !input.value) input.value = qFromUrl;
        input.addEventListener('input', run);
        $$('.js-search-term').forEach(chip => chip.addEventListener('click', () => {
            input.value = chip.dataset.term;
            run();
            input.focus();
        }));
        run();
    }

    /* ---------- Login / cadastro ---------- */
    function initAuth() {
        const tabs = $$('.tabs [role="tab"]');
        if (!tabs.length) return;

        const select = (tab) => {
            tabs.forEach(t => {
                const on = t === tab;
                t.setAttribute('aria-selected', String(on));
                t.tabIndex = on ? 0 : -1;
                $('#' + t.getAttribute('aria-controls')).hidden = !on;
            });
        };
        tabs.forEach(t => t.addEventListener('click', () => select(t)));
        if (location.hash === '#cadastro') select(tabs[1]);

        $$('.js-auth-form').forEach(form => form.addEventListener('submit', (e) => {
            e.preventDefault();
            let ok = true;
            $$('input[required]', form).forEach(inp => {
                const err = inp.parentElement.querySelector('.form-error');
                let msg = '';
                if (!inp.value.trim()) msg = 'Preencha este campo.';
                else if (inp.type === 'email' && !/^\S+@\S+\.\S+$/.test(inp.value)) msg = 'Digite um e-mail válido, como nome@email.com.';
                else if (inp.minLength > 0 && inp.value.length < inp.minLength) msg = `Use pelo menos ${inp.minLength} caracteres.`;
                else if (inp.dataset.match && inp.value !== $('#' + inp.dataset.match).value) msg = 'As senhas não coincidem.';
                inp.classList.toggle('is-invalid', !!msg);
                if (err) err.textContent = msg;
                if (msg) ok = false;
            });
            if (ok) toast(form.dataset.success);
        }));
    }

    /* ---------- Rastreio ---------- */
    function initTracking() {
        const form = $('#track-form');
        if (!form) return;
        const input = $('#track-input');
        const err = $('#track-error');
        const result = $('#track-result');

        // Usados só na versão HTML (sem PHP). Na versão PHP o status vem de api/pedido.php,
        // que lê os pedidos atualizados em Admin > Pedidos.
        const demoOrders = { 'DIM-2026-4821': 'enviado', 'DIM-2026-3517': 'entregue' };
        const steps = [
            ['Pedido confirmado', 'Pagamento aprovado'],
            ['Em produção', 'Sua peça está sendo impressa'],
            ['Controle de qualidade', 'Acabamento e inspeção'],
            ['Em trânsito', 'Enviado pela transportadora'],
            ['Entregue', 'Pedido recebido']
        ];
        const statusInfo = {
            confirmado: { label: 'Confirmado',  step: 1, eta: 'Seu pedido entra em produção em breve' },
            imprimindo: { label: 'Em produção', step: 2, eta: 'Sua peça está na impressora' },
            enviado:    { label: 'Em trânsito', step: 4, eta: 'A caminho do seu endereço' },
            entregue:   { label: 'Entregue',    step: 5, eta: 'Pedido entregue' },
            cancelado:  { label: 'Cancelado',   step: 0, eta: 'Este pedido foi cancelado. Fale com o suporte se tiver dúvidas.' }
        };

        const notFound = () => {
            result.hidden = true;
            err.textContent = 'Não encontramos esse código. Confira o formato DIM-AAAA-0000 ou use o código enviado por e-mail.';
            input.classList.add('is-invalid');
        };

        const show = (code, status) => {
            const info = statusInfo[status];
            if (!info) return notFound();
            err.textContent = '';
            input.classList.remove('is-invalid');
            $('#track-code').textContent = code;
            $('#track-status').textContent = info.label;
            $('#track-eta').textContent = info.eta;
            $('#track-timeline').innerHTML = steps.map((s, i) => `
                <li class="${i < info.step ? 'is-done' : ''}">${s[0]}<small>${s[1]}</small></li>`).join('');
            result.hidden = false;
        };

        const search = async (raw) => {
            const code = raw.trim().toUpperCase();
            if (API) {
                try {
                    const res = await fetch(`${API}pedido.php?codigo=${encodeURIComponent(code)}`);
                    const data = await res.json();
                    return data.ok ? show(data.codigo, data.status) : notFound();
                } catch (_) { /* sem PHP: usa dados de demonstração */ }
            }
            demoOrders[code] ? show(code, demoOrders[code]) : notFound();
        };

        form.addEventListener('submit', (e) => { e.preventDefault(); search(input.value); });
        $$('.js-track-example').forEach(b => b.addEventListener('click', () => {
            input.value = b.textContent;
            search(b.textContent);
        }));
    }

    /* ---------- Newsletter ---------- */
    function initNewsletter() {
        const form = $('#newsletter-form');
        if (!form) return;
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = $('input', form);
            if (!/^\S+@\S+\.\S+$/.test(input.value)) {
                input.classList.add('is-invalid');
                toast('Digite um e-mail válido para assinar');
                return;
            }
            input.classList.remove('is-invalid');
            input.value = '';
            toast('Inscrição confirmada. Até a próxima novidade!');
        });
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSidebar();
        initAddToCart();
        updateCartCount();
        initCartPage();
        initCategoryFilter();
        initSearch();
        initAuth();
        initTracking();
        initNewsletter();
    });
})();
