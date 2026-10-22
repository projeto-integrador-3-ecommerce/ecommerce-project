const accountMenu = document.querySelector('.account-menu');
const sidebar = document.querySelector('#sidebar');
const menuButton = document.querySelector('.js-sidebar-open');
const sidebarCloseButton = sidebar?.querySelector('.js-sidebar-close');

function setSidebarOpen(open) {
    const wasOpen = document.body.classList.contains('sidebar-open');
    document.body.classList.toggle('sidebar-open', open);
    menuButton?.setAttribute('aria-expanded', String(open));
    if (sidebar) {
        sidebar.inert = !open;
        sidebar.setAttribute('aria-hidden', String(!open));
    }

    if (open && !wasOpen) {
        sidebarCloseButton?.focus();
    } else if (!open && wasOpen) {
        menuButton?.focus();
    }
}

menuButton?.addEventListener('click', () => setSidebarOpen(true));
document.querySelectorAll('.js-sidebar-close').forEach((button) => {
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
        if (document.body.classList.contains('sidebar-open')) {
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
    const button = event.target.closest('.js-search-term');
    if (!button) return;

    const input = document.querySelector('#search-input');
    if (input) {
        input.value = button.dataset.term;
        input.form?.requestSubmit();
    }
});

const cartItemCheckboxes = () => [...document.querySelectorAll('[data-cart-select]')];
const selectAllCartItems = document.querySelector('#select-all-cart-items');

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
        if (orderSubmit) {
            orderSubmit.disabled = selectedItems.length === 0;
            orderSubmit.textContent = quantity > 0
                ? `Finalizar compra · ${quantity} ${quantity === 1 ? 'item' : 'itens'}`
                : 'Selecione ao menos um item';
        }
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
    toast.classList.add('is-visible');
    window.clearTimeout(toastTimeout);
    toastTimeout = window.setTimeout(() => toast.classList.remove('is-visible'), 2800);
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
            container.innerHTML = `<div class="empty-state"><h2>Seu carrinho está vazio</h2><p>Explore o catálogo e encontre algo para dar forma às suas ideias.</p><a class="btn btn--primary" href="${container.dataset.catalogUrl}">Ver catálogo</a></div>`;
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
    if (selectedTotal) {
        selectedTotal.textContent = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(total);
    }
    if (orderSubmit) {
        orderSubmit.disabled = selectedItems.length === 0;
        orderSubmit.textContent = quantity > 0
            ? `Finalizar compra · ${quantity} ${quantity === 1 ? 'item' : 'itens'}`
            : 'Selecione ao menos um item';
    }
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
