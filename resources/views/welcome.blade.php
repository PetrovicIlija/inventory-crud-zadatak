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

        .actions { display: flex; gap: 8px; }

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

<div class="toolbar">
    <span id="count-label" style="color:#64748b; font-size:0.875rem;"></span>
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

        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeModal()">Odustani</button>
            <button class="btn-primary" id="save-btn" onclick="saveArticle()">Spremi</button>
        </div>
    </div>
</div>

<script>
    const API = '/api';

    async function loadArticles() {
        const res = await fetch(`${API}/articles`);
        const json = await res.json();
        const articles = json.data;

        const tbody = document.getElementById('articles-body');
        document.getElementById('count-label').textContent = `${articles.length} artikala`;

        if (articles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="color:#94a3b8; text-align:center; padding:32px;">Nema artikala.</td></tr>';
            return;
        }

        tbody.innerHTML = articles.map(a => `
            <tr id="row-${a.id}">
                <td>${escHtml(a.name)}</td>
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

    async function loadCategories() {
        const res = await fetch(`${API}/categories`);
        const json = await res.json();
        const select = document.getElementById('field-category');
        select.innerHTML = '<option value="">— odaberi —</option>' +
            json.data.map(c => `<option value="${c.id}">${escHtml(c.name)}</option>`).join('');
    }

    function openCreate() {
        document.getElementById('modal-title').textContent = 'Novi artikl';
        document.getElementById('article-id').value = '';
        document.getElementById('field-name').value = '';
        document.getElementById('field-quantity').value = '';
        document.getElementById('field-price').value = '';
        document.getElementById('field-category').value = '';
        clearErrors();
        document.getElementById('overlay').classList.add('open');
    }

    function openEdit(id) {
        const row = document.getElementById(`row-${id}`);
        const cells = row.querySelectorAll('td');

        document.getElementById('modal-title').textContent = 'Uredi artikl';
        document.getElementById('article-id').value = id;
        document.getElementById('field-name').value = cells[0].textContent;
        document.getElementById('field-quantity').value = cells[2].textContent;
        document.getElementById('field-price').value = cells[3].textContent;

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
        const body = {
            name:        document.getElementById('field-name').value,
            quantity:    parseInt(document.getElementById('field-quantity').value) || 0,
            price:       parseFloat(document.getElementById('field-price').value) || 0,
            category_id: parseInt(document.getElementById('field-category').value) || null,
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

        closeModal();
        loadArticles();
    }

    async function deleteArticle(id) {
        if (!confirm('Obrisati ovaj artikl?')) return;

        await fetch(`${API}/articles/${id}`, { method: 'DELETE' });
        loadArticles();
    }

    function showErrors(errors) {
        clearErrors();
        const map = { name: 'err-name', category_id: 'err-category', quantity: 'err-quantity', price: 'err-price' };
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
    loadArticles();
</script>
</body>
</html>
