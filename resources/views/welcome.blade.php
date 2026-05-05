<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventar</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            padding: 32px;
        }

        h1 { font-size: 1.5rem; margin-bottom: 24px; }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary { background: #3b82f6; color: #fff; }
        .btn-primary:hover { background: #2563eb; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-secondary { background: #e2e8f0; color: #1e293b; }
        .btn-secondary:hover { background: #cbd5e1; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.875rem;
        }

        th { background: #f8fafc; font-weight: 600; color: #64748b; }
        td:nth-child(3), th:nth-child(3) { text-align: right; }
        td:nth-child(4), th:nth-child(4) { text-align: right; }

        td .description {
            display: block;
            color: #94a3b8;
            font-size: 0.75rem;
            margin-top: 2px;
            font-weight: normal;
        }

        .actions { display: flex; gap: 8px; }

        .filters {
            display: flex;
            gap: 8px;
            align-items: center;
            background: #fff;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            flex-wrap: wrap;
        }
        .filters label { font-size: 0.75rem; font-weight: 600; color: #64748b; }
        .filters select, .filters input[type="text"] {
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.875rem;
            font-family: inherit;
        }
        .filters input[type="text"] { width: 200px; }
        .filters .spacer { flex: 1; }

        /* Modal */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .overlay.open { display: flex; }

        .modal {
            background: #fff;
            border-radius: 10px;
            padding: 28px;
            width: 420px;
            max-width: 95vw;
        }

        .modal h2 { font-size: 1.1rem; margin-bottom: 20px; }

        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 600; color: #64748b; margin-bottom: 4px; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.875rem;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .error-msg { color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: none; }
    </style>
</head>
<body>

<h1>Inventar artikala</h1>

<div class="filters">
    <label for="filter-category">Kategorija:</label>
    <select id="filter-category" onchange="applyFilters()">
        <option value="">— sve —</option>
    </select>

    <label for="filter-search" style="margin-left:12px;">Pretraga:</label>
    <input type="text" id="filter-search" placeholder="naziv artikla..." oninput="searchDebounced()">

    <button class="btn-secondary" onclick="resetFilters()" style="margin-left:8px;">Poništi</button>

    <div class="spacer"></div>
    <span id="count-label" style="color:#64748b; font-size:0.875rem;"></span>
</div>

<div class="toolbar">
    <span style="color:#64748b; font-size:0.875rem;" id="stats-label"></span>
    <button class="btn-primary" onclick="openCreate()">+ Novi artikl</button>
</div>

<table>
    <thead>
        <tr>
            <th>Naziv</th>
            <th>Kategorija</th>
            <th>Količina</th>
            <th>Cijena (€)</th>
            <th>Akcije</th>
        </tr>
    </thead>
    <tbody id="articles-body">
        <tr><td colspan="5" style="color:#94a3b8; text-align:center; padding:32px;">Učitavanje...</td></tr>
    </tbody>
</table>

<!-- Modal -->
<div class="overlay" id="overlay">
    <div class="modal">
        <h2 id="modal-title">Novi artikl</h2>
        <input type="hidden" id="article-id">

        <div class="form-group">
            <label>Naziv</label>
            <input type="text" id="field-name" placeholder="npr. Laptop Dell XPS">
            <div class="error-msg" id="err-name"></div>
        </div>
        <div class="form-group">
            <label>Kategorija</label>
            <select id="field-category">
                <option value="">— odaberi —</option>
            </select>
            <div class="error-msg" id="err-category"></div>
        </div>
        <div class="form-group">
            <label>Količina</label>
            <input type="number" id="field-quantity" min="0" placeholder="0">
            <div class="error-msg" id="err-quantity"></div>
        </div>
        <div class="form-group">
            <label>Cijena (€)</label>
            <input type="number" id="field-price" min="0" step="0.01" placeholder="0.00">
            <div class="error-msg" id="err-price"></div>
        </div>
        <div class="form-group">
            <label>Opis (opcionalno)</label>
            <textarea id="field-description" rows="2" placeholder="kratki opis artikla..." style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.875rem; font-family:inherit; resize:vertical;"></textarea>
            <div class="error-msg" id="err-description"></div>
        </div>

        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeModal()">Odustani</button>
            <button class="btn-primary" id="save-btn" onclick="saveArticle()">Spremi</button>
        </div>
    </div>
</div>

<script>
    const API = '/api';

    async function loadArticles() {
        const tbody = document.getElementById('articles-body');

        // Pripremi query parametre iz filtera
        const params = new URLSearchParams();
        const cat = document.getElementById('filter-category').value;
        const search = document.getElementById('filter-search').value.trim();
        if (cat) params.set('category_id', cat);
        if (search) params.set('search', search);
        const url = params.toString() ? `${API}/articles?${params}` : `${API}/articles`;

        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });

        if (!res.ok) {
            const json = await res.json().catch(() => ({}));
            const hint = json.message ? `<br><small style="font-weight:normal;">${escHtml(json.message)}</small>` : '';
            tbody.innerHTML = `<tr><td colspan="5" style="color:#ef4444; text-align:center; padding:32px;">
                Greška pri učitavanju artikala. Je li implementiran <code>ArticleController@index</code>?${hint}
            </td></tr>`;
            document.getElementById('count-label').textContent = '';
            return;
        }

        const json = await res.json();
        const articles = json.data;

        const filterActive = cat || search;
        document.getElementById('count-label').textContent =
            filterActive ? `${articles.length} rezultata` : `${articles.length} artikala`;

        if (articles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="color:#94a3b8; text-align:center; padding:32px;">Nema artikala.</td></tr>';
            return;
        }

        tbody.innerHTML = articles.map(a => `
            <tr id="row-${a.id}" data-description="${escHtml(a.description ?? '')}">
                <td>
                    ${escHtml(a.name)}
                    ${a.description ? `<span class="description">${escHtml(a.description)}</span>` : ''}
                </td>
                <td>${escHtml(a.category?.name ?? '—')}</td>
                <td>${a.quantity}</td>
                <td>${parseFloat(a.price).toFixed(2)}</td>
                <td class="actions">
                    <button class="btn-secondary" onclick="openEdit(${a.id})">Uredi</button>
                    <button class="btn-danger" onclick="deleteArticle(${a.id})">Obriši</button>
                </td>
            </tr>
        `).join('');
    }

    let searchTimer;
    function searchDebounced() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(loadArticles, 300);
    }

    function applyFilters() {
        loadArticles();
    }

    function resetFilters() {
        document.getElementById('filter-category').value = '';
        document.getElementById('filter-search').value = '';
        loadArticles();
    }

    async function loadStats() {
        const label = document.getElementById('stats-label');
        const res = await fetch(`${API}/articles/stats`, { headers: { 'Accept': 'application/json' } }).catch(() => null);
        if (!res || !res.ok) { label.textContent = ''; return; }
        const s = await res.json();
        label.textContent = `Ukupno: ${s.count} artikala · Vrijednost zaliha: ${parseFloat(s.total_value).toFixed(2)} € · Niska zaliha: ${s.low_stock_count}`;
    }

    async function loadCategories() {
        const res = await fetch(`${API}/categories`, { headers: { 'Accept': 'application/json' } });
        const modalSelect = document.getElementById('field-category');
        const filterSelect = document.getElementById('filter-category');

        if (!res.ok) {
            modalSelect.innerHTML = '<option value="">— greška pri učitavanju kategorija —</option>';
            filterSelect.innerHTML = '<option value="">— sve —</option>';
            return;
        }

        const json = await res.json();
        modalSelect.innerHTML = '<option value="">— odaberi —</option>' +
            json.data.map(c => `<option value="${c.id}">${escHtml(c.name)}</option>`).join('');
        filterSelect.innerHTML = '<option value="">— sve —</option>' +
            json.data.map(c => `<option value="${c.id}">${escHtml(c.name)}</option>`).join('');
    }

    function openCreate() {
        document.getElementById('modal-title').textContent = 'Novi artikl';
        document.getElementById('article-id').value = '';
        document.getElementById('field-name').value = '';
        document.getElementById('field-quantity').value = '';
        document.getElementById('field-price').value = '';
        document.getElementById('field-category').value = '';
        document.getElementById('field-description').value = '';
        clearErrors();
        document.getElementById('overlay').classList.add('open');
    }

    function openEdit(id) {
        const row = document.getElementById(`row-${id}`);
        const cells = row.querySelectorAll('td');
        // Naziv može sadržavati description span — uzmi samo prvi text node
        const nameNode = cells[0].childNodes[0];
        const nameText = nameNode ? nameNode.textContent.trim() : cells[0].textContent.trim();

        document.getElementById('modal-title').textContent = 'Uredi artikl';
        document.getElementById('article-id').value = id;
        document.getElementById('field-name').value = nameText;
        document.getElementById('field-quantity').value = cells[2].textContent;
        document.getElementById('field-price').value = cells[3].textContent;
        document.getElementById('field-description').value = row.dataset.description || '';

        // Pronađi category_id po imenu
        const catName = cells[1].textContent;
        const options = document.getElementById('field-category').options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].text === catName) {
                document.getElementById('field-category').value = options[i].value;
                break;
            }
        }

        clearErrors();
        document.getElementById('overlay').classList.add('open');
    }

    function closeModal() {
        document.getElementById('overlay').classList.remove('open');
    }

    async function saveArticle() {
        const id = document.getElementById('article-id').value;
        const description = document.getElementById('field-description').value.trim();
        const body = {
            name:        document.getElementById('field-name').value,
            quantity:    parseInt(document.getElementById('field-quantity').value) || 0,
            price:       parseFloat(document.getElementById('field-price').value) || 0,
            category_id: parseInt(document.getElementById('field-category').value) || null,
            description: description || null,
        };

        const saveBtn = document.getElementById('save-btn');
        saveBtn.disabled = true;

        const url    = id ? `${API}/articles/${id}` : `${API}/articles`;
        const method = id ? 'PUT' : 'POST';

        const res = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(body),
        });

        saveBtn.disabled = false;

        if (res.status === 422) {
            const json = await res.json();
            showErrors(json.errors);
            return;
        }

        if (!res.ok) {
            const json = await res.json().catch(() => ({}));
            const method = id ? 'update' : 'store';
            alert(`Greška pri spremanju. Je li implementiran ArticleController@${method}?\n\n${json.message ?? ''}`);
            return;
        }

        closeModal();
        refreshAll();
    }

    async function deleteArticle(id) {
        if (!confirm('Obrisati ovaj artikl?')) return;

        await fetch(`${API}/articles/${id}`, { method: 'DELETE' });
        refreshAll();
    }

    function showErrors(errors) {
        clearErrors();
        const map = { name: 'err-name', category_id: 'err-category', quantity: 'err-quantity', price: 'err-price', description: 'err-description' };
        for (const [field, msgs] of Object.entries(errors)) {
            const el = document.getElementById(map[field]);
            if (el) { el.textContent = msgs[0]; el.style.display = 'block'; }
        }
    }

    function clearErrors() {
        document.querySelectorAll('.error-msg').forEach(el => { el.textContent = ''; el.style.display = 'none'; });
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // Init
    loadCategories();
    refreshAll();

    function refreshAll() {
        loadArticles();
        loadStats();
    }
</script>
</body>
</html>
