/*
 * Comportamentos da loja (carrinho via AJAX, busca assíncrona, dropdown de categorias,
 * CEP automático, pagamento). Este arquivo NÃO conhece o visual: ele encontra
 * os elementos por atributos data-* e muda estados por atributos também
 * (data-menu-open no <body>, data-visible no #toast).
 * Veja a tabela de "hooks" no FRONTEND.md antes de remover/renomear algum.
 */

const accountMenu = document.querySelector('[data-account-menu]');
const sidebar = document.querySelector('#sidebar');
const menuButton = document.querySelector('[data-sidebar-open]');
const sidebarFirstLink = sidebar?.querySelector('a[href]');

function setSidebarOpen(open) {
    const wasOpen = document.body.hasAttribute('data-menu-open');
    document.body.toggleAttribute('data-menu-open', open);
    menuButton?.setAttribute('aria-expanded', String(open));
    if (sidebar) {
        sidebar.inert = !open;
        sidebar.setAttribute('aria-hidden', String(!open));
    }

    if (open && !wasOpen) {
        sidebarFirstLink?.focus();
    } else if (!open && wasOpen) {
        menuButton?.focus();
    }
}

menuButton?.addEventListener('click', () => setSidebarOpen(!document.body.hasAttribute('data-menu-open')));
document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
    button.addEventListener('click', () => setSidebarOpen(false));
});
sidebar?.querySelectorAll('a[href]').forEach((link) => {
    link.addEventListener('click', () => setSidebarOpen(false));
});

document.addEventListener('click', (event) => {
    if (accountMenu?.open && !accountMenu.contains(event.target)) {
        accountMenu.open = false;
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        if (document.body.hasAttribute('data-menu-open')) {
            setSidebarOpen(false);
        } else if (accountMenu?.open) {
            accountMenu.open = false;
            accountMenu.querySelector('summary')?.focus();
        }
    }
});

document.querySelectorAll('[data-filter-category]').forEach((button) => {
    button.addEventListener('click', () => {
        const category = button.dataset.filterCategory;

        document.querySelectorAll('[data-filter-category]').forEach((filter) => {
            filter.setAttribute('aria-pressed', String(filter === button));
        });

        document.querySelectorAll('[data-product-category]').forEach((product) => {
            product.hidden = category !== 'all' && product.dataset.productCategory !== category;
        });
    });
});

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-search-term]');
    if (!button) return;

    const input = document.querySelector('#search-input');
    if (input) {
        input.value = button.dataset.term;
        input.form?.requestSubmit();
    }
});

document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('[data-carousel-track]');
    const slides = [...carousel.querySelectorAll('[data-carousel-slide]')];
    const dots = [...carousel.querySelectorAll('[data-carousel-dot]')];
    const previous = carousel.querySelector('[data-carousel-prev]');
    const next = carousel.querySelector('[data-carousel-next]');

    if (!track || slides.length === 0) return;

    let currentIndex = 0;

    function goToSlide(index) {
        currentIndex = (index + slides.length) % slides.length;
        const firstLeft = slides[0].offsetLeft;
        track.scrollTo({ left: slides[currentIndex].offsetLeft - firstLeft, behavior: 'smooth' });
        dots.forEach((dot, dotIndex) => dot.setAttribute('aria-current', String(dotIndex === currentIndex)));
    }

    previous?.addEventListener('click', () => goToSlide(currentIndex - 1));
    next?.addEventListener('click', () => goToSlide(currentIndex + 1));
    dots.forEach((dot, dotIndex) => {
        dot.addEventListener('click', () => goToSlide(Number(dot.dataset.carouselIndex ?? dotIndex)));
    });
});

const cartItemCheckboxes = () => [...document.querySelectorAll('[data-cart-select]')];
const selectAllCartItems = document.querySelector('#select-all-cart-items');
const cartFilterButtons = [...document.querySelectorAll('[data-cart-filter]')];
let activeCartFilter = 'all';

function applyCartFilter() {
    cartFilterButtons.forEach((button) => {
        button.setAttribute('aria-pressed', String(button.dataset.cartFilter === activeCartFilter));
    });

    document.querySelectorAll('[data-cart-item]').forEach((item) => {
        const checkbox = item.querySelector('[data-cart-select]');
        item.hidden = activeCartFilter === 'selected' && !checkbox?.checked;
    });
}

cartFilterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        activeCartFilter = button.dataset.cartFilter ?? 'all';
        applyCartFilter();
    });
});

if (cartItemCheckboxes().length > 0) {
    const selectedCount = document.querySelector('[data-selected-count]');
    const summaryCount = document.querySelector('[data-summary-count]');
    const selectedTotal = document.querySelector('[data-selected-total]');
    const orderSubmit = document.querySelector('[data-order-submit]');
    const currencyFormatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

    function updateCartSelection() {
        const currentCartItems = cartItemCheckboxes();
        const selectedItems = currentCartItems.filter((checkbox) => checkbox.checked);
        const quantity = selectedItems.reduce((total, checkbox) => total + Number(checkbox.dataset.itemQuantity), 0);
        const total = selectedItems.reduce((subtotal, checkbox) => subtotal + Number(checkbox.dataset.itemTotal), 0);
        const allSelected = currentCartItems.length > 0 && selectedItems.length === currentCartItems.length;

        if (selectAllCartItems) {
            selectAllCartItems.checked = allSelected;
            selectAllCartItems.indeterminate = selectedItems.length > 0 && !allSelected;
        }

        if (selectedCount) {
            selectedCount.textContent = `${quantity} ${quantity === 1 ? 'item selecionado' : 'itens selecionados'}`;
        }
        if (summaryCount) summaryCount.textContent = String(quantity);
        if (selectedTotal) selectedTotal.textContent = currencyFormatter.format(total);
        const summaryEstimated = document.querySelector('[data-summary-estimated]');
        if (summaryEstimated) summaryEstimated.textContent = currencyFormatter.format(total);

        if (orderSubmit) {
            orderSubmit.disabled = selectedItems.length === 0;
            orderSubmit.textContent = quantity > 0
                ? `Finalizar compra (${quantity})`
                : 'Selecione ao menos um item';
        }
        applyCartFilter();
    }

    cartItemCheckboxes().forEach((checkbox) => checkbox.addEventListener('change', updateCartSelection));
    selectAllCartItems?.addEventListener('change', () => {
        cartItemCheckboxes().forEach((checkbox) => {
            checkbox.checked = selectAllCartItems.checked;
        });
        updateCartSelection();
    });
    updateCartSelection();
}

const toast = document.querySelector('#toast');
let toastTimeout;

function showToast(message) {
    if (!toast) return;

    toast.textContent = message;
    toast.setAttribute('data-visible', '');
    window.clearTimeout(toastTimeout);
    toastTimeout = window.setTimeout(() => toast.removeAttribute('data-visible'), 2800);
}

function updateCartBadge(quantity) {
    const badge = document.querySelector('[data-cart-count]');
    if (!badge) return;

    badge.textContent = quantity > 99 ? '99+' : String(quantity);
    badge.hidden = quantity < 1;
    badge.setAttribute('aria-label', `${quantity} ${quantity === 1 ? 'item' : 'itens'} no carrinho`);
}

function updateCartPage(data) {
    const item = [...document.querySelectorAll('[data-cart-item]')]
        .find((element) => element.dataset.cartItem === String(data.item_id));

    if (!item) return;

    if (data.item_quantity === null) {
        item.remove();
    } else {
        const checkbox = item.querySelector('[data-cart-select]');
        const quantity = item.querySelector('[data-cart-quantity]');
        const subtotal = item.querySelector('[data-cart-subtotal]');

        item.dataset.quantity = String(data.item_quantity);
        if (checkbox) {
            checkbox.dataset.itemQuantity = String(data.item_quantity);
            checkbox.dataset.itemTotal = String(data.item_total);
        }
        if (quantity) quantity.textContent = String(data.item_quantity);
        if (subtotal) {
            subtotal.textContent = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data.item_total);
        }
    }

    const remainingItems = cartItemCheckboxes();
    if (remainingItems.length === 0) {
        const container = document.querySelector('[data-cart-items-container]');
        const summary = document.querySelector('[data-cart-summary]');
        if (container) {
            container.innerHTML = `<div data-empty-cart class="rounded-3xl border border-aqua bg-white px-6 py-14 text-center shadow-sm"><h2 class="text-xl font-semibold text-navy">Seu carrinho está vazio</h2><p class="mt-2 text-sm text-muted">Explore o catálogo e encontre algo para dar forma às suas ideias.</p><a href="${container.dataset.catalogUrl}" class="mt-6 inline-flex min-h-12 items-center rounded-full bg-navy px-6 text-sm font-semibold text-white transition hover:bg-navy/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Ver catálogo</a></div>`;
        }
        if (summary) summary.hidden = true;
        return;
    }

    const selectedItems = remainingItems.filter((checkbox) => checkbox.checked);
    const quantity = selectedItems.reduce((total, checkbox) => total + Number(checkbox.dataset.itemQuantity), 0);
    const total = selectedItems.reduce((subtotal, checkbox) => subtotal + Number(checkbox.dataset.itemTotal), 0);
    const selectedCount = document.querySelector('[data-selected-count]');
    const summaryCount = document.querySelector('[data-summary-count]');
    const selectedTotal = document.querySelector('[data-selected-total]');
    const orderSubmit = document.querySelector('[data-order-submit]');

    if (selectAllCartItems) {
        selectAllCartItems.checked = selectedItems.length === remainingItems.length;
        selectAllCartItems.indeterminate = selectedItems.length > 0 && selectedItems.length < remainingItems.length;
    }
    if (selectedCount) selectedCount.textContent = `${quantity} ${quantity === 1 ? 'item selecionado' : 'itens selecionados'}`;
    if (summaryCount) summaryCount.textContent = String(quantity);
    const formattedTotal = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(total);
    if (selectedTotal) selectedTotal.textContent = formattedTotal;

    const summaryEstimated = document.querySelector('[data-summary-estimated]');
    if (summaryEstimated) summaryEstimated.textContent = formattedTotal;
    if (orderSubmit) {
        orderSubmit.disabled = selectedItems.length === 0;
        orderSubmit.textContent = quantity > 0
            ? `Finalizar compra (${quantity})`
            : 'Selecione ao menos um item';
    }
    applyCartFilter();
}

document.addEventListener('submit', async (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    if (form.matches('[data-async-search]')) {
        event.preventDefault();
        const searchUrl = new URL(form.action, window.location.origin);
        new FormData(form).forEach((value, key) => searchUrl.searchParams.set(key, String(value)));

        try {
            const response = await fetch(searchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok || !response.headers.get('content-type')?.includes('text/html')) {
                window.location.assign(searchUrl);
                return;
            }

            const html = await response.text();
            const nextDocument = new DOMParser().parseFromString(html, 'text/html');
            const nextContent = nextDocument.querySelector('#conteudo');
            const currentContent = document.querySelector('#conteudo');
            if (!nextContent || !currentContent) {
                window.location.assign(searchUrl);
                return;
            }

            currentContent.replaceWith(nextContent);
            document.title = nextDocument.title;
            window.history.pushState({}, '', response.url);
            nextContent.querySelector('#search-input')?.focus();
        } catch {
            window.location.assign(searchUrl);
        }
        return;
    }

    if (!form.matches('[data-cart-mutation]')) return;

    event.preventDefault();
    const submitButton = event.submitter;
    if (submitButton) submitButton.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: form.method || 'POST',
            body: new FormData(form),
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        const contentType = response.headers.get('content-type') ?? '';

        if (!contentType.includes('application/json')) {
            window.location.assign(response.url);
            return;
        }

        const data = await response.json();
        if (!response.ok) {
            const message = data.message || Object.values(data.errors ?? {}).flat()[0] || 'Não foi possível atualizar o carrinho.';
            showToast(message);
            return;
        }

        updateCartBadge(data.cart_count);
        updateCartPage(data);
        showToast(data.message);
    } catch {
        showToast('Não foi possível atualizar o carrinho. Tente novamente.');
    } finally {
        if (submitButton?.isConnected) submitButton.disabled = false;
    }
});

const cepInput = document.querySelector('#cep');
cepInput?.addEventListener('blur', async () => {
    const cep = cepInput.value.replace(/\D/g, '');
    if (cep.length !== 8) return;

    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        if (!response.ok) return;
        const address = await response.json();
        if (address.erro) return;

        for (const [field, value] of Object.entries({
            street: address.logradouro,
            neighborhood: address.bairro,
            city: address.localidade,
            state: address.uf,
        })) {
            const input = document.querySelector(`#${field}`);
            if (input && value) input.value = value;
        }
    } catch {
        // O preenchimento automático é opcional; os campos continuam editáveis.
    }
});

// ---------- Pagamento (cartão e Pix: apenas front-end, sem integração) ----------
const payment = document.querySelector('[data-payment]');

if (payment) {
    const panels = payment.querySelectorAll('[data-pay-panel]');
    const digits = (value) => value.replace(/\D/g, '');

    function showPanel(method) {
        panels.forEach((panel) => {
            panel.hidden = panel.dataset.payPanel !== method;
        });
    }

    payment.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
        radio.addEventListener('change', () => showPanel(radio.value));
    });
    showPanel(payment.querySelector('input[name="payment_method"]:checked')?.value);

    // Cartões: máscaras, pré-visualização e validação básica
    const masks = {
        number: (value) => digits(value).slice(0, 16).replace(/(\d{4})(?=\d)/g, '$1 '),
        expiry: (value) => {
            const numbers = digits(value).slice(0, 4);
            return numbers.length > 2 ? `${numbers.slice(0, 2)}/${numbers.slice(2)}` : numbers;
        },
        cvv: (value) => digits(value).slice(0, 4),
    };
    const previewDefaults = { number: '•••• •••• •••• ••••', name: 'Nome no cartão', expiry: 'MM/AA' };

    const isExpired = (expiry) => {
        const [month, year] = expiry.split('/').map(Number);
        const now = new Date();
        const currentYear = now.getFullYear() % 100;

        return year < currentYear || (year === currentYear && month < now.getMonth() + 1);
    };

    payment.querySelectorAll('[data-card-form]').forEach((form) => {
        const feedback = form.querySelector('[data-pay-feedback]');

        form.querySelectorAll('[data-field]').forEach((input) => {
            input.addEventListener('input', () => {
                const field = input.dataset.field;

                if (masks[field]) input.value = masks[field](input.value);
                if (field === 'expiry') {
                    input.setCustomValidity(input.value.length === 5 && isExpired(input.value) ? 'Cartão vencido' : '');
                }

                const preview = form.querySelector(`[data-preview="${field}"]`);
                if (preview) preview.textContent = input.value.trim() || previewDefaults[field];
                feedback.hidden = true;
            });
        });

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            feedback.textContent = 'Simulação: nenhum dado do cartão foi enviado ou armazenado.';
            feedback.hidden = false;
        });
    });

    // Pix: QR Code ilustrativo (não é um QR Code válido)
    const pixResult = payment.querySelector('[data-pix-result]');
    const pixQr = payment.querySelector('[data-pix-qr]');
    const pixGenerate = payment.querySelector('[data-pix-generate]');
    const pixCopy = payment.querySelector('[data-pix-copy]');

    function buildFakeQr(seedText) {
        const size = 29;
        let seed = 0;
        for (const char of seedText) seed = (Math.imul(seed, 31) + char.charCodeAt(0)) >>> 0;

        const random = () => {
            seed = (seed + 0x6d2b79f5) >>> 0;
            let t = seed;
            t = Math.imul(t ^ (t >>> 15), t | 1);
            t ^= t + Math.imul(t ^ (t >>> 7), t | 61);

            return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
        };

        const finders = [[0, 0], [size - 7, 0], [0, size - 7]];
        let path = '';

        for (let y = 0; y < size; y++) {
            for (let x = 0; x < size; x++) {
                let dark = random() > 0.5;

                for (const [fx, fy] of finders) {
                    if (x < fx - 1 || x > fx + 7 || y < fy - 1 || y > fy + 7) continue;

                    const rx = x - fx;
                    const ry = y - fy;
                    const inside = rx >= 0 && rx <= 6 && ry >= 0 && ry <= 6;
                    const ring = rx === 0 || rx === 6 || ry === 0 || ry === 6;
                    const core = rx >= 2 && rx <= 4 && ry >= 2 && ry <= 4;
                    dark = inside && (ring || core);
                }

                if (dark) path += `M${x} ${y}h1v1h-1z`;
            }
        }

        return `<svg viewBox="0 0 ${size} ${size}" shape-rendering="crispEdges" aria-hidden="true"><rect width="${size}" height="${size}" fill="#fff"/><path d="${path}" fill="#0a1030"/></svg>`;
    }

    pixGenerate?.addEventListener('click', () => {
        pixQr.innerHTML = buildFakeQr(pixQr.dataset.seed);
        pixResult.hidden = false;
        pixGenerate.hidden = true;
    });

    pixCopy?.addEventListener('click', async () => {
        const input = payment.querySelector('[data-pix-code]');

        try {
            await navigator.clipboard.writeText(input.value);
        } catch {
            input.select();
            document.execCommand('copy');
        }

        pixCopy.textContent = 'Copiado!';
        setTimeout(() => { pixCopy.textContent = 'Copiar'; }, 2000);
    });
}
