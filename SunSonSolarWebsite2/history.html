<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - History & Receipts</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page">
        <h1>History &amp; Receipts</h1>
        <div class="admin-box">
            <h3>Service history</h3>
            <div class="tbl-wrap"><table>
                <thead><tr><th>Date</th><th>Service</th><th>Technician</th><th>Status</th></tr></thead>
                <tbody id="svcRows"></tbody>
            </table></div>
        </div>
        <div class="admin-box">
            <h3>Payments</h3>
            <div class="tbl-wrap"><table>
                <thead><tr><th>Date</th><th>Receipt no.</th><th>Service</th><th>Amount</th><th></th></tr></thead>
                <tbody id="payRows"></tbody>
            </table></div>
        </div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const user = P.init({ roles: ["customer"] });
        if (user) {
            const past = P.bookings().filter(function (b) { return b.customer === user.uname && ["completed", "cancelled", "declined"].indexOf(b.status) !== -1; }).sort(function (a, b) { return P.byWhen(b, a); });
            document.getElementById("svcRows").innerHTML = past.length ? past.map(function (b) {
                return "<tr><td>" + P.fmtDate(b.date) + "</td><td>" + P.esc(b.service) + "</td><td>" + (b.tech ? P.esc(P.nameOf(b.tech)) : "-") + "</td><td>" + P.chip(b.status) + "</td></tr>";
            }).join("") : '<tr><td colspan="4" class="empty">Completed services will show up here.</td></tr>';

            const pays = P.get("ssPayments", []).filter(function (p) { return p.customer === user.uname; }).sort(function (a, b) { return b.date.localeCompare(a.date); });
            document.getElementById("payRows").innerHTML = pays.length ? pays.map(function (p) {
                return "<tr><td>" + P.fmtDate(p.date) + "</td><td>" + P.esc(p.receipt) + "</td><td>" + P.esc(p.service) + "</td><td>" + P.peso(p.amount) +
                    '</td><td><button class="btn-sm" data-rc="' + P.esc(p.id) + '">View receipt</button></td></tr>';
            }).join("") : '<tr><td colspan="5" class="empty">No payments recorded yet.</td></tr>';

            document.getElementById("payRows").addEventListener("click", function (e) {
                const b = e.target.closest("[data-rc]");
                if (!b) return;
                const p = pays.find(function (x) { return x.id === b.dataset.rc; });
                const s = P.settings();
                const dlg = document.createElement("dialog");
                dlg.id = "receiptDlg";
                dlg.className = "receipt";
                dlg.innerHTML = "<h3>" + P.esc(s.company) + '</h3><p class="muted">' + P.esc(s.address) + " | " + P.esc(s.phone) + "</p><dl>" +
                    "<dt>Receipt no.</dt><dd>" + P.esc(p.receipt) + "</dd><dt>Date</dt><dd>" + P.fmtDate(p.date) + "</dd><dt>Customer</dt><dd>" + P.esc(P.fullName(user)) +
                    "</dd><dt>Service</dt><dd>" + P.esc(p.service) + "</dd><dt>Payment</dt><dd>" + P.esc(p.method) + '</dd></dl><div class="total">Total paid: ' + P.peso(p.amount) +
                    '</div><div class="dlg-actions" style="margin-top:16px"><button class="btn-ghost" id="rcClose">Close</button><button id="rcPrint">Print</button></div>';
                document.body.appendChild(dlg);
                dlg.addEventListener("close", function () { dlg.remove(); });
                dlg.showModal();
                dlg.querySelector("#rcClose").onclick = function () { dlg.close(); };
                dlg.querySelector("#rcPrint").onclick = function () {
                    document.body.classList.add("printing");
                    window.print();
                    document.body.classList.remove("printing");
                };
            });
        }
    </script>
</body>
</html>
