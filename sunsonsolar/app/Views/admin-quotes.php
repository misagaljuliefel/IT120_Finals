<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Quote Requests</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
    <link rel="stylesheet"    href="<?= base_url('assets/SunSonSolarPortal.css') ?>" />
</head>
<body style="display:none">
    <main class="page" style="max-width:1250px">
        <h1>Quote Requests</h1>
        <div class="admin-box">
            <div class="toolbar">
                <select id="f" aria-label="Filter by status"><option value="all">All requests</option><option value="new">New</option><option value="contacted">Contacted</option><option value="closed">Closed</option></select>
                <span class="grow muted" id="count"></span>
                <button class="btn-sm btn-ghost" id="csv">Export CSV</button>
            </div>
            <div class="tbl-wrap"><table>
                <thead><tr><th>Received</th><th>Name</th><th>Contact</th><th>Needs</th><th>Status</th><th></th></tr></thead>
                <tbody id="rows"></tbody>
            </table></div>
        </div>
    </main>

    <script src="<?= base_url('assets/SunSonSolarData.js') ?>"></script>
    <script src="<?= base_url('assets/SunSonSolarPortal.js') ?>"></script>
    <script>
        const me = P.init({ roles: ["admin"] });
        if (me) {
            function list() {
                const f = document.getElementById("f").value;
                return P.get("ssQuotes", []).filter(function (q) { return f === "all" || q.status === f; });
            }
            function draw() {
                const l = list();
                document.getElementById("count").textContent = l.length + " request" + (l.length === 1 ? "" : "s");
                document.getElementById("rows").innerHTML = l.map(function (q) {
                    return "<tr><td>" + P.fmtStamp(q.ts) + "</td><td>" + P.esc(q.name) + "</td><td>" + P.esc(q.email) + "<br>" + P.esc(q.phone) + "</td><td><strong>" + P.esc(q.service) + "</strong>" +
                        (q.items && q.items.length ? "<br>" + q.items.map(P.esc).join("<br>") : "") + (q.notes ? '<br><span class="muted">' + P.esc(q.notes) + "</span>" : "") + "</td><td>" +
                        '<select data-st="' + P.esc(q.id) + '">' + ["new", "contacted", "closed"].map(function (s) { return '<option value="' + s + '"' + (s === q.status ? " selected" : "") + ">" + s.charAt(0).toUpperCase() + s.slice(1) + "</option>"; }).join("") +
                        '</select></td><td><button class="btn-sm btn-danger" data-del="' + P.esc(q.id) + '">Delete</button></td></tr>';
                }).join("") || '<tr><td colspan="6" class="empty">No quote requests yet. New requests from the website will appear here.</td></tr>';
            }
            document.getElementById("rows").addEventListener("change", function (e) {
                const s = e.target.closest("[data-st]");
                if (!s) return;
                const all = P.get("ssQuotes", []);
                all.find(function (q) { return q.id === s.dataset.st; }).status = s.value;
                P.set("ssQuotes", all);
                P.toast("Status updated.");
            });
            document.getElementById("rows").addEventListener("click", function (e) {
                const b = e.target.closest("[data-del]");
                if (!b || !confirm("Delete this quote request?")) return;
                P.set("ssQuotes", P.get("ssQuotes", []).filter(function (q) { return q.id !== b.dataset.del; }));
                draw();
            });
            document.getElementById("f").onchange = draw;
            document.getElementById("csv").onclick = function () {
                P.exportCsv("quote-requests.csv", [["Received", "Name", "Email", "Phone", "Service", "Items", "Notes", "Status"]].concat(list().map(function (q) {
                    return [q.ts, q.name, q.email, q.phone, q.service, (q.items || []).join("; "), q.notes, q.status];
                })));
            };
            draw();
        }
    </script>
</body>
</html>
