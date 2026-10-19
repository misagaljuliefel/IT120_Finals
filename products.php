<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Products & Services<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="dash-layout">
        <aside class="side-panel">
            <h3>Categories</h3>
            <ul class="cat-list" id="catList"></ul>
        </aside>
        <main class="dash-main">
            <div class="dash-toolbar">
                <button class="tab-btn active" data-type="all">All</button>
                <button class="tab-btn" data-type="product">Products</button>
                <button class="tab-btn" data-type="service">Services</button>
                <input type="search" id="searchBox" placeholder="Search products and services..." aria-label="Search" />
            </div>
            <div class="product-grid" id="catalogGrid"></div>
            <div class="quote-section">
                <h3>My Quote List</h3>
                <div id="quoteList"></div>
                <p style="margin-top:14px"><a class="btn-link" href="<?= base_url('quote') ?>">Request a free quote</a></p>
            </div>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        P.init({ public: true });

        const cats = [];
        CATALOG.forEach(function (c) { if (cats.indexOf(c.cat) === -1) cats.push(c.cat); });
        const initial = new URLSearchParams(location.search).get("cat");
        let activeCat = cats.indexOf(initial) !== -1 ? initial : "all";
        let activeType = "all";

        function drawCats() {
            let h = '<li><button data-cat="all"' + (activeCat === "all" ? ' class="active"' : "") + '>All Items</button></li>';
            cats.forEach(function (c) {
                h += '<li><button data-cat="' + P.esc(c) + '"' + (activeCat === c ? ' class="active"' : "") + ">" + P.esc(c) + "</button></li>";
            });
            document.getElementById("catList").innerHTML = h;
        }

        function renderCatalog() {
            const q = document.getElementById("searchBox").value.trim().toLowerCase();
            const cart = getQuoteList();
            const items = CATALOG.filter(function (i) {
                if (activeCat !== "all" && i.cat !== activeCat) return false;
                if (activeType !== "all" && i.type !== activeType) return false;
                return !q || i.name.toLowerCase().indexOf(q) !== -1 || i.cat.toLowerCase().indexOf(q) !== -1;
            });
            let html = "";
            items.forEach(function (i) {
                const inCart = cart.some(function (c) { return c.id === i.id; });
                html += '<div class="product-card"><div class="card-img-container"><img src="' + P.esc(i.img) + '" alt="' + P.esc(i.name) +
                    '" class="card-img" loading="lazy" onerror="this.onerror=null;this.src=P.NO_IMG" /></div><div class="card-content">' +
                    '<span class="card-badge">' + P.esc(i.cat) + "</span><h4>" + P.esc(i.name) + "</h4>" +
                    '<p style="font-weight:bold;color:#d97706;font-size:16px;margin-bottom:8px">' + (i.type === "service" ? "From " : "") + P.peso(i.price) + "</p>" +
                    "<p>" + P.esc(i.desc) + "</p>" +
                    '<button class="read-more-btn" data-add="' + P.esc(i.id) + '" style="border:none;cursor:pointer;width:100%;' + (inCart ? "background:#ccc;" : "") + '">' +
                    (inCart ? "Added to quote" : "Add to quote") + "</button>" +
                    (i.type === "service" ? '<a class="read-more-btn" style="margin-top:8px;width:100%;text-align:center" href="<?= base_url('schedule') ?>?service=' + encodeURIComponent(i.id) + '">Book this service</a>' : "") +
                    "</div></div>";
            });
            document.getElementById("catalogGrid").innerHTML = html || '<p class="empty" style="grid-column:1/-1;color:#fff">No items match your search.</p>';
        }

        function renderQuoteList() {
            const list = getQuoteList();
            document.getElementById("quoteList").innerHTML = list.length ? list.map(function (i) {
                return '<div class="quote-row"><span>' + P.esc(i.name) + '</span><button data-remove="' + P.esc(i.id) + '">Remove</button></div>';
            }).join("") : '<p class="muted">Your quote list is empty. Add products or services above.</p>';
        }

        document.getElementById("catalogGrid").addEventListener("click", function (e) {
            const b = e.target.closest("[data-add]");
            if (!b) return;
            addToQuoteList(CATALOG.find(function (c) { return c.id === b.dataset.add; }));
            renderCatalog(); renderQuoteList();
        });
        document.getElementById("quoteList").addEventListener("click", function (e) {
            const b = e.target.closest("[data-remove]");
            if (!b) return;
            removeFromQuoteList(b.dataset.remove);
            renderQuoteList(); renderCatalog();
        });
        document.getElementById("catList").addEventListener("click", function (e) {
            const b = e.target.closest("button[data-cat]");
            if (!b) return;
            activeCat = b.dataset.cat; drawCats(); renderCatalog();
        });
        document.querySelector(".dash-toolbar").addEventListener("click", function (e) {
            const b = e.target.closest("button[data-type]");
            if (!b) return;
            document.querySelectorAll(".tab-btn").forEach(function (x) { x.classList.remove("active"); });
            b.classList.add("active"); activeType = b.dataset.type; renderCatalog();
        });
        document.getElementById("searchBox").addEventListener("input", renderCatalog);

        drawCats(); renderCatalog(); renderQuoteList();
    </script>
<?= $this->endSection() ?>