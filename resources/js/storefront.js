const body = document.body;
const sidebar = document.querySelector('#sidebar');
const menuButton = document.querySelector('.js-sidebar-open');

function setSidebarOpen(open) {
    body.classList.toggle('sidebar-open', open);
    menuButton?.setAttribute('aria-expanded', String(open));
}

document.querySelectorAll('.js-sidebar-open').forEach((button) => {
    button.addEventListener('click', () => setSidebarOpen(true));
});

document.querySelectorAll('.js-sidebar-close').forEach((button) => {
    button.addEventListener('click', () => setSidebarOpen(false));
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        setSidebarOpen(false);
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

document.querySelectorAll('.js-search-term').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.querySelector('#search-input');
        if (input) {
            input.value = button.dataset.term;
            input.form?.requestSubmit();
        }
    });
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
