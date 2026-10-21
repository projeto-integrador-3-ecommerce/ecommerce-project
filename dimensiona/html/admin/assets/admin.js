/* =========================================================
   Dimensiona — Painel admin
   - Versão PHP: window.ADMIN.api aponta para api.php e tudo é gravado em /data/*.json
   - Versão HTML: sem API; as mudanças ficam salvas só neste navegador (modo demonstração)
   ========================================================= */
(function () {
    'use strict';

    const $  = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
    const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const money = (v, cents = true) => Number(v || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL', minimumFractionDigits: cents ? 2 : 0, maximumFractionDigits: cents ? 2 : 0 });
    const num = (v, d = 0) => Number(v || 0).toLocaleString('pt-BR', { minimumFractionDigits: d, maximumFractionDigits: d });
    const fmtDate = (iso) => { const [y, m, d] = String(iso).split('-'); return `${d}/${m}/${y}`; };
    const initials = (name) => name.split(/\s+/).filter(Boolean).slice(0, 2).map(w => w[0]).join('').toUpperCase();

    /* ---------- Página de login (não tem window.ADMIN) ---------- */
    $$('.js-toggle-pass').forEach(btn => btn.addEventListener('click', () => {
        const input = btn.previousElementSibling;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Esconder senha' : 'Mostrar senha');
    }));
    const loginForm = $('.js-login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            const email = $('#email', loginForm), senha = $('#senha', loginForm);
            email.classList.toggle('is-invalid', !/^\S+@\S+\.\S+$/.test(email.value));
            senha.classList.toggle('is-invalid', !senha.value);
            if (email.classList.contains('is-invalid') || senha.classList.contains('is-invalid')) {
                e.preventDefault();
                return;
            }
            if (loginForm.dataset.demo) { e.preventDefault(); location.href = 'index.html'; }
        });
    }

    if (!window.ADMIN) return;

    /* ---------- Estado ---------- */
    const ADMIN = window.ADMIN;
    const DEMO_KEY = 'dimensiona_admin_demo_v2';
    const isDemo = !ADMIN.api;
    let state = ADMIN.data;
    if (isDemo) {
        try { const saved = JSON.parse(localStorage.getItem(DEMO_KEY)); if (saved) state = { ...state, ...saved }; } catch (_) { /* ignora */ }
    }
    const saveDemo = () => {
        if (!isDemo) return;
        try {
            const { painel, ...rest } = state;
            localStorage.setItem(DEMO_KEY, JSON.stringify(rest));
        } catch (_) { toast('O navegador não permitiu salvar os dados de demonstração.', true); }
    };
    const src = (img) => /^(data:|https?:)/.test(img || '') ? img : ADMIN.root + img;
    const catName = (slug) => (state.categorias.find(c => c.slug === slug) || {}).name || '—';
    const productById = (id) => state.produtos.find(p => Number(p.id) === Number(id));

    const STATUS = {
        confirmado: 'Confirmado', imprimindo: 'Imprimindo', enviado: 'Enviado', entregue: 'Entregue', cancelado: 'Cancelado'
    };
    const PALETTE = ['#1f7bff', '#19c3ff', '#9b7bff', '#f2b500', '#2ed47a', '#ff5a4e', '#8b95c4'];

    /* ---------- Toast ---------- */
    let toastTimer;
    function toast(msg, error = false) {
        const el = $('#a-toast');
        el.textContent = msg;
        el.classList.toggle('is-error', error);
        el.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => el.classList.remove('is-visible'), error ? 4200 : 2800);
    }

    /* ---------- Menu lateral (celular) ---------- */
    $$('.js-side-open').forEach(b => b.addEventListener('click', () => document.body.classList.add('side-open')));
    $$('.js-side-close').forEach(b => b.addEventListener('click', () => document.body.classList.remove('side-open')));

    const updated = $('#a-updated');
    if (updated) updated.textContent = 'Atualizado em ' + new Date().toLocaleString('pt-BR', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });

    /* ---------- Modal ---------- */
    const modal = $('#modal');
    let lastFocus = null;
    function openModal(title, html, { small = false } = {}) {
        lastFocus = document.activeElement;
        $('#modal-title').textContent = title;
        $('#modal-body').innerHTML = html;
        modal.classList.toggle('modal--sm', small);
        modal.hidden = false;
        const first = $('input:not([type=hidden]):not([type=radio]), select, textarea, button:not([data-close])', $('#modal-body'));
        (first || $('[data-close]', modal)).focus();
        return $('#modal-body');
    }
    function closeModal() {
        modal.hidden = true;
        $('#modal-body').innerHTML = '';
        if (lastFocus) lastFocus.focus();
    }
    modal.addEventListener('click', (e) => { if (e.target.closest('[data-close]')) closeModal(); });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.hidden) closeModal();
        if (e.key === 'Tab' && !modal.hidden) {
            const f = $$('button, input, select, textarea, a[href]', modal).filter(el => !el.disabled && el.offsetParent !== null);
            if (!f.length) return;
            if (e.shiftKey && document.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
            else if (!e.shiftKey && document.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
        }
    });

    /** Janela de confirmação. Resolve true se o usuário confirmar. */
    function confirmBox({ title, text, action = 'Excluir', danger = true }) {
        return new Promise(resolve => {
            const body = openModal('', `
                <div class="confirm">
                    <div class="confirm__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 3 2 20h20z"/><path d="M12 10v4M12 17v.5"/></svg></div>
                    <h3>${esc(title)}</h3>
                    <p>${text}</p>
                    <div class="modal__actions">
                        <button type="button" class="a-btn ${danger ? 'a-btn--danger' : 'a-btn--grad'}" data-ok>${esc(action)}</button>
                        <button type="button" class="a-btn a-btn--outline" data-cancel>Cancelar</button>
                    </div>
                </div>`, { small: true });
            $('#modal-title').textContent = title;
            $('#modal-title').classList.add('sr-only');
            const done = (v) => { $('#modal-title').classList.remove('sr-only'); closeModal(); resolve(v); };
            $('[data-ok]', body).addEventListener('click', () => done(true));
            $('[data-cancel]', body).addEventListener('click', () => done(false));
            $('[data-ok]', body).focus();
        });
    }

    /* ---------- Comunicação com a API ---------- */
    async function request(action, payload) {
        if (isDemo) {
            const result = demo[action](payload);
            commit(action, result);
            saveDemo();
            return result;
        }
        let res, json;
        try {
            res = await fetch(`${ADMIN.api}?action=${encodeURIComponent(action)}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': ADMIN.csrf },
                body: JSON.stringify(payload)
            });
            json = await res.json();
        } catch (_) {
            throw new Error('Sem resposta do servidor. Confira sua conexão e tente de novo.');
        }
        if (res.status === 401) { location.href = 'login.php'; throw new Error(json.erro); }
        if (!json.ok) throw new Error(json.erro || 'Não foi possível salvar.');
        commit(action, json.data);
        return json.data;
    }

    function upsert(list, key, rec) {
        const i = list.findIndex(x => String(x[key]) === String(rec[key]));
        if (i >= 0) list[i] = rec; else list.push(rec);
    }

    /** Aplica o resultado de uma ação ao estado local. */
    function commit(action, r) {
        switch (action) {
            case 'produto.salvar':   upsert(state.produtos, 'id', r); break;
            case 'produto.excluir':  state.produtos = state.produtos.filter(p => Number(p.id) !== Number(r.id)); break;
            case 'categoria.salvar': upsert(state.categorias, 'slug', r); break;
            case 'categoria.excluir': state.categorias = state.categorias.filter(c => c.slug !== r.slug); break;
            case 'pedido.status':    upsert(state.pedidos, 'codigo', r); break;
            case 'impressora.acao':  upsert(state.impressoras, 'id', r); break;
            case 'material.salvar':  upsert(state.materiais, 'id', r); break;
            case 'loja.salvar':      state.loja = r; break;
        }
    }

    /** Regras do modo demonstração (espelham admin/api.php). */
    const slugify = (t) => t.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'categoria';
    const nextId = (list) => list.reduce((m, x) => Math.max(m, Number(x.id)), 0) + 1;
    const demo = {
        'produto.salvar': (p) => p.id
            ? { ...productById(p.id), ...p }
            : { rating: 5, reviews: 0, launch: new Date().toLocaleDateString('pt-BR', { month: 'short', year: 'numeric' }).replace('.', '').replace(' de ', ' ').replace(/^./, c => c.toUpperCase()), ...p, id: nextId(state.produtos) },
        'produto.excluir': ({ id }) => ({ id }),
        'categoria.salvar': (c) => {
            if (c.slug) return { ...state.categorias.find(x => x.slug === c.slug), ...c };
            let slug = slugify(c.name), base = slug, i = 2;
            while (state.categorias.some(x => x.slug === slug)) slug = `${base}-${i++}`;
            return { ...c, slug };
        },
        'categoria.excluir': ({ slug }) => {
            const n = state.produtos.filter(p => p.category === slug).length;
            if (n) throw new Error(`Esta categoria tem ${n} produto(s). Mova ou exclua os produtos antes.`);
            return { slug };
        },
        'pedido.status': ({ codigo, status }) => ({ ...state.pedidos.find(p => p.codigo === codigo), status }),
        'impressora.acao': ({ id, acao }) => {
            const imp = { ...state.impressoras.find(i => i.id === id) };
            imp.status = { pausar: 'pausada', retomar: 'imprimindo', manutencao: 'manutencao', liberar: 'ociosa' }[acao];
            if (acao === 'liberar') Object.assign(imp, { job: null, progresso: 0, restante: null });
            return imp;
        },
        'material.salvar': (m) => {
            if (m.id) {
                const cur = { ...state.materiais.find(x => x.id === m.id) };
                cur.kg = Math.round((cur.kg + Number(m.repor)) * 100) / 100;
                cur.capacidade = Math.max(cur.capacidade, cur.kg);
                return cur;
            }
            return { ...m, id: nextId(state.materiais), minimo: Math.round(m.capacidade * 2) / 10 };
        },
        'loja.salvar': ({ section, values: v }) => {
            const l = JSON.parse(JSON.stringify(state.loja));
            if (section === 'info') Object.assign(l, v);
            if (section === 'frete') Object.assign(l, { frete_gratis_min: +v.frete_gratis_min, frete_padrao: +v.frete_padrao, prazo_entrega: +v.prazo_entrega, cep_origem: v.cep_origem });
            if (section === 'cupom') l.cupom = { codigo: v.codigo.toUpperCase().replace(/[^A-Z0-9]/g, ''), desconto: +v.desconto || 0, minimo: +v.minimo || 0 };
            if (section === 'manutencao') l.manutencao = !!v.ativo;
            return l;
        }
    };

    /* =========================================================
       Gráficos em SVG
       ========================================================= */
    // Largura real do elemento: o texto dos eixos fica sempre em 11px, em qualquer tela
    let W = 720;
    const H = 240, PAD = { l: 52, r: 14, t: 14, b: 30 };
    const sizeTo = (el) => { W = Math.max(300, Math.round(el.clientWidth || 720)); };

    /** Topo do eixo em 4 passos redondos (ex.: 0, 5, 10, 15, 20). */
    function niceMax(v) {
        if (v <= 0) return 4;
        const raw = (v * 1.05) / 4;
        const p = Math.pow(10, Math.floor(Math.log10(raw)));
        const step = [1, 2, 2.5, 5, 10].map(m => m * p).find(m => m >= raw);
        return step * 4;
    }
    const shortNum = (v) => v >= 1000 ? num(v / 1000, v % 1000 ? 1 : 0) + 'k' : num(v, v % 1 ? 1 : 0);

    function frame(max, labels, fmt) {
        const pw = W - PAD.l - PAD.r, ph = H - PAD.t - PAD.b;
        let g = '';
        for (let i = 0; i <= 4; i++) {
            const y = PAD.t + ph - (ph * i) / 4;
            g += `<line class="grid-line" x1="${PAD.l}" x2="${W - PAD.r}" y1="${y}" y2="${y}"/>
                  <text class="axis" x="${PAD.l - 8}" y="${y + 4}" text-anchor="end">${(fmt || shortNum)((max * i) / 4)}</text>`;
        }
        return { pw, ph, g };
    }

    function legend(series) {
        return series.length > 1
            ? `<div class="legend">${series.map(s => `<span><i style="background:${s.color}"></i>${esc(s.name)}</span>`).join('')}</div>`
            : '';
    }

    function attachTip(el, points) {
        const w = W;
        const svg = $('svg', el);
        let tip = $('.chart-tip', el);
        if (!tip) { tip = document.createElement('div'); tip.className = 'chart-tip'; tip.hidden = true; el.appendChild(tip); }
        svg.addEventListener('mousemove', (e) => {
            const r = svg.getBoundingClientRect();
            const x = ((e.clientX - r.left) / r.width) * w;
            const hit = points.find(p => x >= p.x0 && x < p.x1);
            if (!hit) { tip.hidden = true; return; }
            tip.innerHTML = hit.html;
            tip.hidden = false;
            tip.style.left = ((hit.cx / w) * r.width) + 'px';
            tip.style.top = ((hit.cy / H) * r.height) + 'px';
        });
        svg.addEventListener('mouseleave', () => { tip.hidden = true; });
    }

    /** Linhas (com área opcional) + barras opcionais na mesma escala. */
    function lineChart(el, { labels, series, bars = [], fmt, tipFmt, min = 0 }) {
        if (!el) return;
        sizeTo(el);
        el._redraw = () => lineChart(el, { labels, series, bars, fmt, tipFmt, min });
        const all = [...series, ...bars].flatMap(s => s.values);
        const max = niceMax(Math.max(...all));
        const lo = min;
        const { pw, ph, g } = frame(max - lo, labels, v => (fmt || shortNum)(v + lo));
        const step = pw / labels.length;
        const X = (i) => PAD.l + step * i + step / 2;
        const Y = (v) => PAD.t + ph - ((v - lo) / (max - lo)) * ph;
        let body = '', defs = '';

        bars.forEach((b, bi) => {
            const bw = Math.min(28, step * 0.35);
            b.values.forEach((v, i) => {
                body += `<rect x="${X(i) - bw / 2 + bi * bw}" y="${Y(v)}" width="${bw}" height="${PAD.t + ph - Y(v)}" rx="2" fill="${b.color}" opacity=".9"/>`;
            });
        });
        series.forEach((s, si) => {
            const pts = s.values.map((v, i) => [X(i), Y(v)]);
            const d = pts.map((p, i) => (i ? 'L' : 'M') + p[0].toFixed(1) + ' ' + p[1].toFixed(1)).join(' ');
            if (s.area) {
                defs += `<linearGradient id="ga${si}${el.id}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="${s.color}" stop-opacity=".35"/><stop offset="1" stop-color="${s.color}" stop-opacity="0"/></linearGradient>`;
                body += `<path d="${d} L${pts[pts.length - 1][0]} ${PAD.t + ph} L${pts[0][0]} ${PAD.t + ph} Z" fill="url(#ga${si}${el.id})"/>`;
            }
            body += `<path d="${d}" fill="none" stroke="${s.color}" stroke-width="2.5" stroke-linejoin="round" ${s.dashed ? 'stroke-dasharray="6 6" opacity=".7"' : ''}/>`;
            if (!s.dashed) body += pts.map(p => `<circle cx="${p[0]}" cy="${p[1]}" r="3.5" fill="var(--color-bg)" stroke="${s.color}" stroke-width="2"/>`).join('');
        });
        const xl = labels.map((l, i) => `<text class="axis" x="${X(i)}" y="${H - 8}" text-anchor="middle">${esc(l)}</text>`).join('');

        el.innerHTML = `<svg viewBox="0 0 ${W} ${H}" role="img" aria-label="${esc(el.dataset.label || 'Gráfico')}"><defs>${defs}</defs>${g}${body}${xl}</svg>${legend([...series, ...bars])}`;
        const f = tipFmt || fmt || num;
        attachTip(el, labels.map((l, i) => ({
            x0: PAD.l + step * i, x1: PAD.l + step * (i + 1), cx: X(i),
            cy: Math.min(...[...series, ...bars].map(s => Y(s.values[i]))),
            html: `<strong>${esc(l)}</strong><br>` + [...series, ...bars].map(s => `${esc(s.name)}: ${f(s.values[i])}`).join('<br>')
        })));
    }

    /** Barras agrupadas ou empilhadas. */
    function barChart(el, { labels, series, stacked = false, fmt, highlightMax = false }) {
        if (!el) return;
        sizeTo(el);
        el._redraw = () => barChart(el, { labels, series, stacked, fmt, highlightMax });
        const totals = labels.map((_, i) => series.reduce((s, x) => s + x.values[i], 0));
        const max = niceMax(stacked ? Math.max(...totals) : Math.max(...series.flatMap(s => s.values)));
        const { pw, ph, g } = frame(max, labels, fmt);
        const step = pw / labels.length;
        const Y = (v) => (v / max) * ph;
        const groupW = Math.min(step * 0.7, stacked ? 56 : 26 * series.length + 8);
        let body = '';
        const peak = Math.max(...(series[0] || { values: [0] }).values);

        labels.forEach((_, i) => {
            const gx = PAD.l + step * i + (step - groupW) / 2;
            let acc = 0;
            series.forEach((s, si) => {
                const v = s.values[i];
                const h = Y(v);
                const color = highlightMax && v === peak ? '#19c3ff' : s.color;
                if (stacked) {
                    body += `<rect x="${gx}" y="${PAD.t + ph - Y(acc) - h}" width="${groupW}" height="${h}" fill="${color}" ${si === series.length - 1 ? 'rx="2"' : ''}/>`;
                    acc += v;
                } else {
                    const bw = (groupW - (series.length - 1) * 3) / series.length;
                    body += `<rect x="${gx + si * (bw + 3)}" y="${PAD.t + ph - h}" width="${bw}" height="${h}" rx="2" fill="${color}"/>`;
                }
            });
        });
        const xl = labels.map((l, i) => `<text class="axis" x="${PAD.l + step * i + step / 2}" y="${H - 8}" text-anchor="middle">${esc(l)}</text>`).join('');
        el.innerHTML = `<svg viewBox="0 0 ${W} ${H}" role="img" aria-label="${esc(el.dataset.label || 'Gráfico')}">${g}${body}${xl}</svg>${legend(series)}`;
        const f = fmt || num;
        attachTip(el, labels.map((l, i) => ({
            x0: PAD.l + step * i, x1: PAD.l + step * (i + 1), cx: PAD.l + step * i + step / 2,
            cy: PAD.t + ph - Y(stacked ? totals[i] : Math.max(...series.map(s => s.values[i]))),
            html: `<strong>${esc(l)}</strong><br>` + series.map(s => `${esc(s.name)}: ${f(s.values[i])}`).join('<br>')
        })));
    }

    function donut(el, items, { center, sub }) {
        if (!el) return;
        const total = items.reduce((s, x) => s + x.value, 0) || 1;
        const R = 70, C = 2 * Math.PI * R;
        let offset = 0, arcs = '';
        items.forEach(it => {
            const len = (it.value / total) * C;
            arcs += `<circle cx="85" cy="85" r="${R}" fill="none" stroke="${it.color}" stroke-width="20" stroke-dasharray="${Math.max(len - 2, 0)} ${C}" stroke-dashoffset="${-offset}" transform="rotate(-90 85 85)"><title>${esc(it.label)}: ${it.value}%</title></circle>`;
            offset += len;
        });
        el.innerHTML = `
            <div class="donut">
                <svg viewBox="0 0 170 170" role="img" aria-label="Vendas por categoria">
                    <circle cx="85" cy="85" r="${R}" fill="none" stroke="var(--color-bg-deep)" stroke-width="20"/>
                    ${arcs}
                    <text class="donut__center" x="85" y="88" text-anchor="middle">${esc(center)}</text>
                    <text class="donut__sub" x="85" y="106" text-anchor="middle">${esc(sub)}</text>
                </svg>
                <ul class="donut__legend">${items.map(it => `<li><i style="background:${it.color}"></i>${esc(it.label)}<b>${it.value}%</b></li>`).join('')}</ul>
            </div>`;
    }

    /* ---------- Blocos reutilizáveis ---------- */
    function kpi(label, value, { color = '', trend = null, invert = false, foot = 'vs. mês anterior', center = false } = {}) {
        let t = '';
        if (trend !== null) {
            const good = invert ? trend <= 0 : trend >= 0;
            t = `<span class="trend ${good ? 'trend--up' : 'trend--down'}">${trend >= 0 ? '▲' : '▼'} ${num(Math.abs(trend), 1)}%</span>`;
        }
        return `<div class="kpi${center ? ' kpi--center' : ''}">
            <p class="kpi__label">${esc(label)}</p>
            <p class="kpi__value${color ? ' kpi__value--' + color : ''}">${value}</p>
            <p class="kpi__foot">${t}<span>${esc(foot)}</span></p>
        </div>`;
    }
    const pill = (status, label) => `<span class="pill pill--${esc(status)}">${esc(label || status)}</span>`;
    const orderThumb = (o) => {
        const p = productById((o.itens[0] || {}).produto_id);
        return p ? `<img class="thumb" src="${esc(src(p.img))}" alt="">` : `<span class="thumb" aria-hidden="true"></span>`;
    };
    const itemCount = (o) => o.itens.reduce((s, i) => s + i.qtd, 0);
    const byDateDesc = (a, b) => b.data.localeCompare(a.data) || b.codigo.localeCompare(a.codigo);

    /* =========================================================
       Páginas
       ========================================================= */
    const pages = {};

    /* ---------- Visão geral ---------- */
    pages['visao-geral'] = function () {
        const P = state.painel, k = P.kpis;
        $('#kpis').innerHTML =
            kpi('Receita do mês', money(k.receita, false), { trend: k.receita_var }) +
            kpi('Pedidos no mês', num(k.pedidos_mes), { trend: k.pedidos_var, color: 'white' }) +
            kpi('Ticket médio', money(k.ticket), { trend: k.ticket_var, color: 'green' }) +
            kpi('Taxa de cancelamento', num(k.cancelamento, 1) + '%', { trend: k.cancelamento_var, invert: true, color: 'yellow' });

        lineChart($('#chart-receita'), {
            labels: P.meses,
            series: [{ name: 'Receita', values: P.receita, color: '#19c3ff', area: true }, { name: 'Meta', values: P.meta, color: '#8b95c4', dashed: true }],
            tipFmt: v => money(v, false)
        });

        const cats = state.categorias.map((c, i) => ({ label: c.name, value: P.vendas_categoria[c.slug] || 0, color: PALETTE[i % PALETTE.length] })).filter(c => c.value);
        donut($('#chart-categorias'), cats, { center: num(k.pedidos_mes), sub: 'pedidos' });

        const sem = P.pedidos_semana, colors = ['#1f5bff', '#19c3ff', '#ff5a4e'];
        barChart($('#chart-semana'), { labels: P.dias, series: Object.keys(sem).map((n, i) => ({ name: n, values: sem[n], color: colors[i] })) });

        $('#recent-orders').innerHTML = [...state.pedidos].sort(byDateDesc).slice(0, 5).map(o => `
            <li>
                ${orderThumb(o)}
                <div class="list__main"><strong>${esc(o.codigo)}</strong><span>${esc(o.cliente)} · ${fmtDate(o.data)}</span></div>
                <div class="list__end"><strong>${money(o.total)}</strong>${pill(o.status, STATUS[o.status])}</div>
            </li>`).join('') || '<li class="empty">Nenhum pedido ainda.</li>';

        const top = P.top_produtos.map(t => ({ ...t, p: productById(t.id) })).filter(t => t.p);
        const topMax = Math.max(1, ...top.map(t => t.vendas));
        $('#top-products').innerHTML = top.map(t => `
            <li>
                <img class="thumb" src="${esc(src(t.p.img))}" alt="">
                <div class="list__main"><strong>${esc(t.p.name)}</strong><span>${t.vendas} vendas</span>
                    <div class="bar"><span style="width:${(t.vendas / topMax) * 100}%"></span></div></div>
            </li>`).join('') || '<li class="empty">Sem vendas registradas.</li>';

        const low = state.materiais.filter(m => m.kg <= m.minimo);
        $('#stock-alerts').hidden = !low.length;
        $('#stock-alert-list').innerHTML = low.map(m => `
            <a href="estoque.${isDemo ? 'html' : 'php'}"><strong>${esc(m.nome)}</strong><span>Restam ${num(m.kg, 1)} kg · mínimo ${num(m.minimo, 1)} kg</span></a>`).join('');
    };

    /* ---------- Pedidos ---------- */
    let orderFilter = 'todos';
    pages.pedidos = function () {
        const list = state.pedidos;
        const count = (s) => list.filter(o => o.status === s).length;

        $('#order-tabs').innerHTML = [['todos', 'Todos', list.length], ...Object.entries(STATUS).map(([k, v]) => [k, v, count(k)])]
            .map(([k, v, n]) => `<button type="button" role="tab" data-status="${k}" aria-selected="${orderFilter === k}">${v}<small>${n}</small></button>`).join('');

        $('#order-kpis').innerHTML =
            kpi('Confirmados', count('confirmado'), { foot: 'aguardando produção', center: true }) +
            kpi('Imprimindo', count('imprimindo'), { foot: 'nas impressoras', color: 'white', center: true }) +
            kpi('Enviados', count('enviado'), { foot: 'em trânsito', color: 'yellow', center: true }) +
            kpi('Entregues', count('entregue'), { foot: 'concluídos', color: 'green', center: true });

        const q = ($('#order-search').value || '').toLowerCase();
        const rows = [...list].sort(byDateDesc)
            .filter(o => orderFilter === 'todos' || o.status === orderFilter)
            .filter(o => !q || (o.codigo + ' ' + o.cliente).toLowerCase().includes(q));

        $('#orders-body').innerHTML = rows.map(o => `
            <tr>
                <td><div class="cell-flex">${orderThumb(o)}<div><strong>${esc(o.codigo)}</strong><span class="muted">${esc(o.cliente)}</span></div></div></td>
                <td class="muted">${fmtDate(o.data)}</td>
                <td>${itemCount(o)}</td>
                <td class="num">${money(o.total)}</td>
                <td>
                    <select class="a-input pill pill--${o.status} pill-select js-order-status" data-codigo="${esc(o.codigo)}" aria-label="Status do pedido ${esc(o.codigo)}">
                        ${Object.entries(STATUS).map(([k, v]) => `<option value="${k}" ${k === o.status ? 'selected' : ''}>${v}</option>`).join('')}
                    </select>
                </td>
                <td class="actions"><button type="button" class="a-link js-order-details" data-codigo="${esc(o.codigo)}">Detalhes</button></td>
            </tr>`).join('');
        $('#orders-empty').hidden = rows.length > 0;

        lineChart($('#chart-pedidos'), { labels: state.painel.meses, series: [{ name: 'Pedidos', values: state.painel.pedidos_mensais, color: '#19c3ff', area: true }] });
    };

    function bindOrders() {
        $('#order-tabs').addEventListener('click', (e) => {
            const b = e.target.closest('[data-status]');
            if (b) { orderFilter = b.dataset.status; pages.pedidos(); }
        });
        $('#order-search').addEventListener('input', () => pages.pedidos());
        document.addEventListener('change', async (e) => {
            const sel = e.target.closest('.js-order-status');
            if (!sel) return;
            await changeStatus(sel.dataset.codigo, sel.value);
        });
        document.addEventListener('click', (e) => {
            const b = e.target.closest('.js-order-details');
            if (b) orderDetails(b.dataset.codigo);
        });
    }

    async function changeStatus(codigo, status) {
        if (status === 'cancelado' && !(await confirmBox({ title: 'Cancelar pedido?', text: `O pedido ${esc(codigo)} será marcado como cancelado e o cliente verá isso no rastreio.`, action: 'Cancelar pedido' }))) {
            return render();
        }
        try {
            await request('pedido.status', { codigo, status });
            toast(`Pedido ${codigo} agora está como "${STATUS[status]}". O rastreio da loja já mostra o novo status.`);
        } catch (err) { toast(err.message, true); }
        render();
    }

    function orderDetails(codigo) {
        const o = state.pedidos.find(x => x.codigo === codigo);
        const body = openModal(`Pedido ${o.codigo}`, `
            <div class="order-meta">
                <div><span>Cliente</span>${esc(o.cliente)}</div>
                <div><span>E-mail</span>${esc(o.email || '—')}</div>
                <div><span>Data</span>${fmtDate(o.data)}</div>
                <div><span>Total</span>${money(o.total)}</div>
            </div>
            <ul class="order-items">
                ${o.itens.map(it => { const p = productById(it.produto_id); return `
                    <li>${p ? `<img class="thumb" src="${esc(src(p.img))}" alt="">` : ''}<span>${esc(p ? p.name : 'Produto removido')} × ${it.qtd}</span><span>${p ? money(p.price * it.qtd) : ''}</span></li>`; }).join('')}
            </ul>
            <div class="a-field" style="margin-top:16px">
                <label for="d-status">Status</label>
                <select class="a-input" id="d-status">${Object.entries(STATUS).map(([k, v]) => `<option value="${k}" ${k === o.status ? 'selected' : ''}>${v}</option>`).join('')}</select>
            </div>
            <div class="modal__actions">
                <button type="button" class="a-btn a-btn--grad" data-save>Salvar status</button>
                <button type="button" class="a-btn a-btn--outline" data-close>Fechar</button>
            </div>`);
        $('[data-save]', body).addEventListener('click', async () => {
            const s = $('#d-status', body).value;
            closeModal();
            if (s !== o.status) await changeStatus(o.codigo, s);
        });
    }

    /* ---------- Produtos ---------- */
    pages.produtos = function () {
        const q = ($('#product-search').value || '').toLowerCase();
        const rows = state.produtos.filter(p => !q || (p.name + ' ' + catName(p.category)).toLowerCase().includes(q));
        const n = state.produtos.length;
        $('#product-count').textContent = `${n} ${n === 1 ? 'produto cadastrado' : 'produtos cadastrados'}`;
        $('#products-body').innerHTML = rows.map(p => `
            <tr>
                <td><img class="thumb" src="${esc(src(p.img))}" alt=""></td>
                <td><strong>${esc(p.name)}</strong></td>
                <td class="num">${money(p.price)}${p.old_price ? ` <span class="muted"><s>${money(p.old_price)}</s></span>` : ''}</td>
                <td class="muted">${esc(catName(p.category))}</td>
                <td>${p.badge ? pill(p.badge, p.badge === 'novo' ? 'Novo' : 'Oferta') : '<span class="muted">—</span>'}</td>
                <td>${pill(p.status || 'ativo', (p.status || 'ativo') === 'ativo' ? 'Ativo' : 'Inativo')}</td>
                <td class="actions">
                    <button type="button" class="a-link js-edit-product" data-id="${p.id}">Editar</button>
                    <button type="button" class="a-link a-link--danger js-del-product" data-id="${p.id}">Excluir</button>
                </td>
            </tr>`).join('');
        $('#products-empty').hidden = rows.length > 0;
    };

    function bindProducts() {
        $('#product-search').addEventListener('input', () => pages.produtos());
        $('#new-product').addEventListener('click', () => productForm());
        document.addEventListener('click', async (e) => {
            const ed = e.target.closest('.js-edit-product');
            if (ed) return productForm(productById(ed.dataset.id));
            const del = e.target.closest('.js-del-product');
            if (!del) return;
            const p = productById(del.dataset.id);
            if (!(await confirmBox({ title: 'Confirmar exclusão', text: `Excluir "${esc(p.name)}"? Ele sai da loja na hora. Esta ação não pode ser desfeita.` }))) return;
            try { await request('produto.excluir', { id: p.id }); toast(`"${p.name}" foi excluído.`); }
            catch (err) { toast(err.message, true); }
            render();
        });
    }

    function productForm(p = null) {
        const edit = !!p;
        p = p || { name: '', price: '', old_price: '', category: (state.categorias[0] || {}).slug, badge: null, img: '', desc: '', status: 'ativo' };
        const images = [...new Set(['assets/img/placeholder.svg', ...state.produtos.map(x => x.img)])];
        if (p.img && !images.includes(p.img)) images.unshift(p.img);
        if (!p.img) p.img = images[0];

        const pickerHtml = (list, selected) => list.map((img, i) => `
            <label title="Imagem ${i + 1}"><input type="radio" name="img" value="${esc(img)}" ${img === selected ? 'checked' : ''}><img src="${esc(src(img))}" alt="Imagem ${i + 1}"></label>`).join('');

        const body = openModal(edit ? 'Editar produto' : 'Novo produto', `
            <form novalidate>
                <p class="a-label" style="margin-bottom:8px">Imagem</p>
                <div class="img-picker" id="img-picker">
                    ${pickerHtml(images, p.img)}
                    <label class="img-upload">+ Enviar imagem<input type="file" accept="image/png,image/jpeg,image/webp" id="img-file" hidden></label>
                </div>
                <div class="a-field" style="margin-top:16px"><label for="f-name">Nome do produto</label><input class="a-input" id="f-name" value="${esc(p.name)}" maxlength="120" required></div>
                <div class="a-row">
                    <div class="a-field"><label for="f-price">Preço (R$)</label><input class="a-input" type="number" step="0.01" min="0" id="f-price" value="${esc(p.price)}" required></div>
                    <div class="a-field"><label for="f-old">Preço antigo (opcional)</label><input class="a-input" type="number" step="0.01" min="0" id="f-old" value="${esc(p.old_price ?? '')}"></div>
                </div>
                <div class="a-row">
                    <div class="a-field"><label for="f-cat">Categoria</label>
                        <select class="a-input" id="f-cat">${state.categorias.map(c => `<option value="${esc(c.slug)}" ${c.slug === p.category ? 'selected' : ''}>${esc(c.name)}</option>`).join('')}</select></div>
                    <div class="a-field"><label for="f-status">Status na loja</label>
                        <select class="a-input" id="f-status"><option value="ativo">Ativo (visível)</option><option value="inativo" ${p.status === 'inativo' ? 'selected' : ''}>Inativo (oculto)</option></select></div>
                </div>
                <div class="a-field"><label for="f-desc">Descrição</label><textarea class="a-input" id="f-desc" maxlength="400">${esc(p.desc)}</textarea></div>
                <div class="btn-row" style="margin-bottom:12px">
                    <label class="a-check"><input type="checkbox" id="f-new" ${p.badge === 'novo' ? 'checked' : ''}> Marcar como NOVO</label>
                    <label class="a-check"><input type="checkbox" id="f-sale" ${p.badge === 'oferta' ? 'checked' : ''}> Marcar como PROMOÇÃO</label>
                </div>
                <p class="a-error" id="f-error" role="alert"></p>
                <div class="modal__actions">
                    <button type="submit" class="a-btn a-btn--grad">${edit ? 'Salvar alterações' : 'Criar produto'}</button>
                    <button type="button" class="a-btn a-btn--outline" data-close>Cancelar</button>
                </div>
            </form>`);

        // Badge é único: marcar um desmarca o outro
        const cNew = $('#f-new', body), cSale = $('#f-sale', body);
        cNew.addEventListener('change', () => { if (cNew.checked) cSale.checked = false; });
        cSale.addEventListener('change', () => { if (cSale.checked) cNew.checked = false; });

        $('#img-file', body).addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            try {
                const img = await uploadImage(file);
                images.unshift(img);
                const upload = $('.img-upload', body);
                $$('#img-picker > label:not(.img-upload)', body).forEach(l => l.remove());
                upload.insertAdjacentHTML('beforebegin', pickerHtml(images, img));
                toast('Imagem enviada.');
            } catch (err) { toast(err.message, true); }
            e.target.value = '';
        });

        $('form', body).addEventListener('submit', async (e) => {
            e.preventDefault();
            const err = $('#f-error', body);
            const data = {
                id: edit ? p.id : undefined,
                name: $('#f-name', body).value.trim(),
                price: parseFloat($('#f-price', body).value),
                old_price: $('#f-old', body).value ? parseFloat($('#f-old', body).value) : null,
                category: $('#f-cat', body).value,
                status: $('#f-status', body).value,
                desc: $('#f-desc', body).value.trim(),
                badge: cNew.checked ? 'novo' : cSale.checked ? 'oferta' : null,
                img: ($('input[name=img]:checked', body) || {}).value
            };
            $$('.a-input', body).forEach(i => i.classList.remove('is-invalid'));
            const bad = (id, msg) => { $(id, body).classList.add('is-invalid'); $(id, body).focus(); err.textContent = msg; };
            if (!data.name) return bad('#f-name', 'Informe o nome do produto.');
            if (!(data.price > 0)) return bad('#f-price', 'Informe um preço maior que zero.');
            if (data.old_price !== null && data.old_price <= data.price) return bad('#f-old', 'O preço antigo precisa ser maior que o preço atual.');
            if (!data.category) return bad('#f-cat', 'Crie uma categoria antes de cadastrar produtos.');
            if (!data.img) { err.textContent = 'Escolha uma imagem.'; return; }
            try {
                const saved = await request('produto.salvar', data);
                closeModal();
                toast(edit ? `"${saved.name}" foi atualizado na loja.` : `"${saved.name}" foi criado e já aparece na loja.`);
                render();
            } catch (ex) { err.textContent = ex.message; }
        });
    }

    async function uploadImage(file) {
        if (!/^image\/(png|jpeg|webp)$/.test(file.type)) throw new Error('Use uma imagem JPG, PNG ou WEBP.');
        if (isDemo) {
            if (file.size > 800 * 1024) throw new Error('No modo demonstração a imagem precisa ter até 800 KB.');
            return new Promise((res, rej) => { const r = new FileReader(); r.onload = () => res(r.result); r.onerror = () => rej(new Error('Não foi possível ler a imagem.')); r.readAsDataURL(file); });
        }
        if (file.size > 3 * 1024 * 1024) throw new Error('A imagem precisa ter até 3 MB.');
        const fd = new FormData();
        fd.append('imagem', file);
        const res = await fetch(`${ADMIN.api}?action=upload`, { method: 'POST', headers: { 'X-CSRF-Token': ADMIN.csrf }, body: fd });
        const json = await res.json().catch(() => ({}));
        if (!json.ok) throw new Error(json.erro || 'Não foi possível enviar a imagem.');
        return json.data.img;
    }

    /* ---------- Categorias ---------- */
    pages.categorias = function () {
        const n = state.categorias.length;
        $('#category-count').textContent = `${n} ${n === 1 ? 'categoria' : 'categorias'}`;
        $('#category-grid').innerHTML = state.categorias.map(c => {
            const qtd = state.produtos.filter(p => p.category === c.slug).length;
            return `
            <article class="cat-item">
                <div class="cat-item__top">
                    <span class="cat-item__img" aria-hidden="true">Imagem</span>
                    <div>
                        <h3>${esc(c.name)}</h3>
                        <p>${esc(c.desc)}</p>
                    </div>
                </div>
                <p class="cat-item__meta">${qtd} ${qtd === 1 ? 'produto' : 'produtos'} nesta categoria</p>
                <div class="cat-item__actions">
                    <button type="button" class="a-btn a-btn--outline a-btn--sm js-edit-cat" data-slug="${esc(c.slug)}">Editar</button>
                    <button type="button" class="a-btn a-btn--danger-outline a-btn--sm js-del-cat" data-slug="${esc(c.slug)}">Excluir</button>
                </div>
            </article>`;
        }).join('') || '<p class="empty">Nenhuma categoria. Crie a primeira para começar a cadastrar produtos.</p>';
    };

    function bindCategories() {
        $('#new-category').addEventListener('click', () => categoryForm());
        document.addEventListener('click', async (e) => {
            const ed = e.target.closest('.js-edit-cat');
            if (ed) return categoryForm(state.categorias.find(c => c.slug === ed.dataset.slug));
            const del = e.target.closest('.js-del-cat');
            if (!del) return;
            const c = state.categorias.find(x => x.slug === del.dataset.slug);
            const qtd = state.produtos.filter(p => p.category === c.slug).length;
            if (qtd) return toast(`"${c.name}" tem ${qtd} produto(s). Mova ou exclua os produtos antes de excluir a categoria.`, true);
            if (!(await confirmBox({ title: 'Confirmar exclusão', text: `Excluir a categoria "${esc(c.name)}"? Esta ação não pode ser desfeita.` }))) return;
            try { await request('categoria.excluir', { slug: c.slug }); toast(`Categoria "${c.name}" excluída.`); }
            catch (err) { toast(err.message, true); }
            render();
        });
    }

    function categoryForm(c = null) {
        const edit = !!c;
        c = c || { name: '', desc: '' };
        const body = openModal(edit ? 'Editar categoria' : 'Nova categoria', `
            <form novalidate>
                <div class="a-field"><label for="c-name">Nome</label><input class="a-input" id="c-name" value="${esc(c.name)}" maxlength="60" required></div>
                <div class="a-field"><label for="c-desc">Descrição curta</label><input class="a-input" id="c-desc" value="${esc(c.desc)}" maxlength="160" placeholder="Aparece no card da categoria na loja"></div>
                <p class="a-error" id="c-error" role="alert"></p>
                <div class="modal__actions">
                    <button type="submit" class="a-btn a-btn--grad">${edit ? 'Salvar alterações' : 'Criar categoria'}</button>
                    <button type="button" class="a-btn a-btn--outline" data-close>Cancelar</button>
                </div>
            </form>`);
        $('form', body).addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = $('#c-name', body).value.trim();
            if (!name) { $('#c-name', body).classList.add('is-invalid'); $('#c-error', body).textContent = 'Informe o nome da categoria.'; return; }
            try {
                const saved = await request('categoria.salvar', {
                    slug: edit ? c.slug : undefined, name,
                    desc: $('#c-desc', body).value.trim()
                });
                closeModal();
                toast(edit ? `Categoria "${saved.name}" atualizada.` : `Categoria "${saved.name}" criada.`);
                render();
            } catch (ex) { $('#c-error', body).textContent = ex.message; }
        });
    }

    /* ---------- Impressoras ---------- */
    const PRINTER_STATUS = { imprimindo: 'Imprimindo', pausada: 'Pausada', ociosa: 'Ociosa', manutencao: 'Manutenção' };
    pages.impressoras = function () {
        const L = state.impressoras, c = (s) => L.filter(i => i.status === s).length;
        $('#printer-kpis').innerHTML =
            kpi('Imprimindo', c('imprimindo'), { foot: `de ${L.length} impressoras`, center: true }) +
            kpi('Ociosas', c('ociosa'), { foot: 'prontas para um novo job', color: 'white', center: true }) +
            kpi('Paradas', c('manutencao') + c('pausada'), { foot: 'pausadas ou em manutenção', color: 'yellow', center: true });

        $('#printer-grid').innerHTML = L.map(p => {
            const job = p.job ? `
                <div>
                    <p class="printer__job"><span>${esc(p.job)}</span><strong>${p.progresso}%</strong></p>
                    <div class="bar"><span style="width:${p.progresso}%"></span></div>
                    <p class="printer__job" style="margin-top:6px"><span>${p.status === 'pausada' ? 'Pausada' : 'Restam ' + esc(p.restante || '—')}</span><span>${esc(p.material)}</span></p>
                </div>
                <div class="printer__temps">
                    ${p.bico != null ? `<div><strong>${p.bico}°C</strong><span>Bico</span></div>` : `<div><strong>${esc(p.material.split(' ')[0])}</strong><span>Material</span></div>`}
                    <div><strong>${p.mesa != null ? p.mesa + '°C' : '—'}</strong><span>${p.tipo === 'SLA' ? 'Tanque' : 'Mesa'}</span></div>
                </div>` : `<p class="printer__idle">${p.status === 'manutencao' ? 'Em manutenção' : 'Aguardando próximo job · ' + esc(p.material)}</p>`;
            const btn = {
                imprimindo: `<button type="button" class="a-btn a-btn--danger-outline a-btn--block js-printer" data-id="${p.id}" data-acao="pausar">Pausar</button>`,
                pausada: `<div class="btn-row"><button type="button" class="a-btn a-btn--grad js-printer" style="flex:1" data-id="${p.id}" data-acao="retomar">Retomar</button><button type="button" class="a-btn a-btn--outline js-printer" style="flex:1" data-id="${p.id}" data-acao="liberar">Cancelar job</button></div>`,
                ociosa: `<button type="button" class="a-btn a-btn--outline a-btn--block js-printer" data-id="${p.id}" data-acao="manutencao">Colocar em manutenção</button>`,
                manutencao: `<button type="button" class="a-btn a-btn--outline a-btn--block js-printer" data-id="${p.id}" data-acao="liberar">Finalizar manutenção</button>`
            }[p.status] || '';
            return `
            <article class="printer">
                <div class="printer__head">
                    <div><h3>${esc(p.id)} ${pill(p.status, PRINTER_STATUS[p.status])}</h3><p>${esc(p.tipo)} · ${esc(p.modelo)}</p></div>
                </div>
                ${job}
                ${btn}
            </article>`;
        }).join('');

        barChart($('#chart-horas'), { labels: state.painel.dias, series: [{ name: 'Horas', values: state.painel.horas_impressao, color: '#1f5bff' }], highlightMax: true, fmt: v => num(v) + 'h' });
    };

    function bindPrinters() {
        document.addEventListener('click', async (e) => {
            const b = e.target.closest('.js-printer');
            if (!b) return;
            const msgs = { pausar: 'pausada', retomar: 'retomada', manutencao: 'em manutenção', liberar: 'liberada' };
            if (b.dataset.acao === 'liberar' && b.textContent.includes('Cancelar') &&
                !(await confirmBox({ title: 'Cancelar impressão?', text: 'O job atual será descartado e a impressora ficará livre.', action: 'Cancelar job' }))) return;
            try { await request('impressora.acao', { id: b.dataset.id, acao: b.dataset.acao }); toast(`${b.dataset.id} ${msgs[b.dataset.acao]}.`); }
            catch (err) { toast(err.message, true); }
            render();
        });
    }

    /* ---------- Estoque ---------- */
    pages.estoque = function () {
        const M = state.materiais;
        const low = M.filter(m => m.kg <= m.minimo).length;
        const value = M.reduce((s, m) => s + m.kg * m.preco_kg, 0);
        $('#stock-kpis').innerHTML =
            kpi('Materiais cadastrados', M.length, { foot: 'filamentos e resinas', center: true }) +
            kpi('Estoque baixo', low, { foot: 'abaixo do mínimo', color: low ? 'red' : 'green', center: true }) +
            kpi('Valor em estoque', money(value, false), { foot: 'preço de custo', color: 'green', center: true }) +
            kpi('Pedidos de compra', state.painel.kpis.pedidos_compra, { foot: 'aguardando entrega', color: 'yellow', center: true });

        $('#materials-body').innerHTML = M.map(m => {
            const pct = Math.min(100, (m.kg / m.capacidade) * 100);
            const lvl = m.kg <= m.minimo ? 'bar--low' : pct < 45 ? 'bar--mid' : '';
            return `
            <tr>
                <td><strong>${esc(m.nome)}</strong> ${m.kg <= m.minimo ? pill('baixo', 'Baixo') : ''}</td>
                <td class="muted">${esc(m.tipo)}</td>
                <td style="min-width:180px">
                    <div class="bar ${lvl}" style="margin:0 0 4px"><span style="width:${pct}%"></span></div>
                    <span class="muted">${num(m.kg, 1)} de ${num(m.capacidade, 1)} kg</span>
                </td>
                <td class="num">${money(m.preco_kg)}</td>
                <td class="actions"><button type="button" class="a-link js-restock" data-id="${m.id}">Repor</button></td>
            </tr>`;
        }).join('');

        const C = state.painel.consumo, colors = ['#1f5bff', '#19c3ff', '#9b7bff'];
        barChart($('#chart-consumo'), { labels: state.painel.meses, stacked: true, series: Object.keys(C).map((n, i) => ({ name: n, values: C[n], color: colors[i % 3] })), fmt: v => num(v) + ' kg' });
    };

    function bindStock() {
        $('#new-material').addEventListener('click', materialForm);
        document.addEventListener('click', (e) => {
            const b = e.target.closest('.js-restock');
            if (!b) return;
            const m = state.materiais.find(x => x.id === Number(b.dataset.id));
            const body = openModal(`Repor ${m.nome}`, `
                <form novalidate>
                    <p class="panel__text">Em estoque: ${num(m.kg, 1)} kg · mínimo ${num(m.minimo, 1)} kg</p>
                    <div class="a-field"><label for="r-qtd">Quantidade recebida (kg)</label><input class="a-input" type="number" step="0.1" min="0.1" id="r-qtd" value="${num(Math.max(m.capacidade - m.kg, 1), 1).replace(',', '.')}" required></div>
                    <p class="a-error" id="r-error" role="alert"></p>
                    <div class="modal__actions">
                        <button type="submit" class="a-btn a-btn--grad">Registrar entrada</button>
                        <button type="button" class="a-btn a-btn--outline" data-close>Cancelar</button>
                    </div>
                </form>`, { small: true });
            $('form', body).addEventListener('submit', async (ev) => {
                ev.preventDefault();
                const qtd = parseFloat($('#r-qtd', body).value);
                if (!(qtd > 0)) { $('#r-error', body).textContent = 'Informe uma quantidade maior que zero.'; return; }
                try { await request('material.salvar', { id: m.id, repor: qtd }); closeModal(); toast(`Entrada de ${num(qtd, 1)} kg registrada em ${m.nome}.`); render(); }
                catch (ex) { $('#r-error', body).textContent = ex.message; }
            });
        });
    }

    function materialForm() {
        const body = openModal('Adicionar material', `
            <form novalidate>
                <div class="a-field"><label for="m-nome">Nome</label><input class="a-input" id="m-nome" placeholder="Ex: PLA Azul 1kg" required></div>
                <div class="a-row">
                    <div class="a-field"><label for="m-tipo">Tecnologia</label><select class="a-input" id="m-tipo"><option>FDM</option><option>SLA</option></select></div>
                    <div class="a-field"><label for="m-preco">Preço por kg (R$)</label><input class="a-input" type="number" step="0.01" min="0" id="m-preco"></div>
                </div>
                <div class="a-row">
                    <div class="a-field"><label for="m-kg">Quantidade atual (kg)</label><input class="a-input" type="number" step="0.1" min="0" id="m-kg" value="0"></div>
                    <div class="a-field"><label for="m-cap">Capacidade (kg)</label><input class="a-input" type="number" step="0.1" min="0.1" id="m-cap" required></div>
                </div>
                <p class="a-error" id="m-error" role="alert"></p>
                <div class="modal__actions">
                    <button type="submit" class="a-btn a-btn--grad">Adicionar material</button>
                    <button type="button" class="a-btn a-btn--outline" data-close>Cancelar</button>
                </div>
            </form>`);
        $('form', body).addEventListener('submit', async (e) => {
            e.preventDefault();
            const d = { nome: $('#m-nome', body).value.trim(), tipo: $('#m-tipo', body).value, preco_kg: parseFloat($('#m-preco', body).value) || 0, kg: parseFloat($('#m-kg', body).value) || 0, capacidade: parseFloat($('#m-cap', body).value) };
            const err = $('#m-error', body);
            if (!d.nome) { err.textContent = 'Informe o nome do material.'; return; }
            if (!(d.capacidade > 0)) { err.textContent = 'Informe a capacidade em kg.'; return; }
            try { await request('material.salvar', d); closeModal(); toast(`${d.nome} adicionado ao estoque.`); render(); }
            catch (ex) { err.textContent = ex.message; }
        });
    }

    /* ---------- Clientes ---------- */
    pages.clientes = function () {
        const C = state.painel.clientes;
        $('#client-kpis').innerHTML =
            kpi('Total de clientes', num(C.total), { trend: C.total_var }) +
            kpi('Novos no mês', num(C.novos), { trend: C.novos_var, color: 'white' }) +
            kpi('Taxa de recorrência', C.recorrencia + '%', { trend: C.recorrencia_var, color: 'green' }) +
            kpi('NPS', C.nps, { trend: C.nps_var, color: 'yellow', foot: 'pontos vs. mês anterior' });

        lineChart($('#chart-clientes'), {
            labels: state.painel.meses,
            series: [{ name: 'Total', values: C.crescimento_total, color: '#19c3ff', area: true }],
            bars: [{ name: 'Novos', values: C.crescimento_novos, color: '#1f5bff' }]
        });

        $('#best-clients').innerHTML = C.melhores.map(c => `
            <li>
                <span class="avatar" aria-hidden="true">${esc(initials(c.nome))}</span>
                <div class="list__main"><strong>${esc(c.nome)}${c.vip ? '<span class="tag">VIP</span>' : ''}</strong><span>${c.pedidos} pedidos</span></div>
                <div class="list__end"><strong>${money(c.total)}</strong></div>
            </li>`).join('');

        lineChart($('#chart-retencao'), { labels: state.painel.meses, series: [{ name: 'Retenção', values: C.retencao, color: '#2ed47a' }], fmt: v => Math.round(v) + '%', min: 0 });

        const total = C.avaliacoes.reduce((s, v) => s + v, 0) || 1;
        $('#ratings').innerHTML = C.avaliacoes.map((v, i) => `
            <li><span>${5 - i} ★</span><div class="bar"><span style="width:${(v / total) * 100}%"></span></div><em>${num(v)}</em></li>`).join('');
    };

    /* ---------- Configurações ---------- */
    pages.configuracoes = function () {
        const L = state.loja;
        const set = (sec, vals) => { const f = $(`.js-settings[data-section="${sec}"]`); Object.entries(vals).forEach(([k, v]) => { if (f.elements[k]) f.elements[k].value = v ?? ''; }); };
        set('info', { nome: L.nome, cnpj: L.cnpj, email: L.email, telefone: L.telefone });
        set('frete', { frete_gratis_min: L.frete_gratis_min, frete_padrao: L.frete_padrao, prazo_entrega: L.prazo_entrega, cep_origem: L.cep_origem });
        set('cupom', { codigo: L.cupom.codigo, desconto: L.cupom.desconto, minimo: L.cupom.minimo });
        $('#maint-text').textContent = L.manutencao
            ? 'A loja está em manutenção: visitantes veem um aviso e não conseguem comprar. Você continua vendo a loja normalmente enquanto estiver logado.'
            : 'Ações que afetam toda a loja. O modo manutenção esconde a loja dos visitantes até você desativá-lo.';
        const btn = $('#toggle-maint');
        btn.textContent = L.manutencao ? 'Reabrir a loja' : 'Colocar loja em manutenção';
        btn.className = 'a-btn ' + (L.manutencao ? 'a-btn--grad' : 'a-btn--danger-outline');
    };

    function bindSettings() {
        $$('.js-settings').forEach(form => form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const values = Object.fromEntries(new FormData(form));
            const bad = $$('input[required]', form).find(i => !i.value.trim()) || (form.elements.email && !/^\S+@\S+\.\S+$/.test(values.email) && form.elements.email);
            $$('.a-input', form).forEach(i => i.classList.remove('is-invalid'));
            if (bad) { bad.classList.add('is-invalid'); bad.focus(); return toast('Confira os campos destacados.', true); }
            if (form.dataset.section === 'cupom' && values.codigo && !(+values.desconto >= 1 && +values.desconto <= 90)) {
                form.elements.desconto.classList.add('is-invalid'); return toast('O desconto precisa estar entre 1% e 90%.', true);
            }
            try {
                await request('loja.salvar', { section: form.dataset.section, values });
                toast(isDemo ? 'Configurações salvas neste navegador (modo demonstração).' : 'Configurações salvas. A loja já usa os novos valores.');
                render();
            } catch (err) { toast(err.message, true); }
        }));

        $('#toggle-maint').addEventListener('click', async () => {
            const on = !state.loja.manutencao;
            const ok = await confirmBox(on
                ? { title: 'Colocar loja em manutenção?', text: 'Visitantes vão ver um aviso de manutenção e não conseguirão comprar até você reabrir.', action: 'Ativar manutenção' }
                : { title: 'Reabrir a loja?', text: 'A loja volta a ficar visível para todos os visitantes.', action: 'Reabrir loja', danger: false });
            if (!ok) return;
            try { await request('loja.salvar', { section: 'manutencao', values: { ativo: on } }); toast(on ? 'Loja em manutenção.' : 'Loja reaberta.'); render(); }
            catch (err) { toast(err.message, true); }
        });

        $('#export-data').addEventListener('click', () => {
            if (!isDemo) { location.href = `${ADMIN.api}?action=exportar`; return; }
            const { painel, ...data } = state;
            const a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' }));
            a.download = `dimensiona-dados-${new Date().toISOString().slice(0, 10)}.json`;
            a.click();
            URL.revokeObjectURL(a.href);
        });
    }

    /* ---------- Inicialização ---------- */
    const page = document.body.dataset.page;
    const render = () => pages[page] && pages[page]();
    const binders = { pedidos: bindOrders, produtos: bindProducts, categorias: bindCategories, impressoras: bindPrinters, estoque: bindStock, configuracoes: bindSettings };

    if (binders[page]) binders[page]();
    render();

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => $$('.chart').forEach(el => el._redraw && el._redraw()), 150);
    });

    if (isDemo) {
        const a = document.createElement('p');
        a.className = 'a-head__meta';
        a.classList.add('text-yellow', 'mt-0.5');
        a.textContent = 'Modo demonstração: as alterações ficam salvas só neste navegador.';
        const h = $('.a-head > div:nth-child(2)');
        if (h) h.appendChild(a);
    }
})();
