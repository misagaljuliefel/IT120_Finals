<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Reports</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page" style="max-width:1200px">
        <h1>Reports</h1>
        <div class="tiles" id="tiles"></div>
        <div class="chart-grid">
            <div class="admin-box"><h3>Bookings per month</h3><div id="cMonth"></div></div>
            <div class="admin-box"><h3>Revenue per month</h3><div id="cRev"></div></div>
            <div class="admin-box"><h3>Bookings by status</h3><div id="cStatus"></div></div>
            <div class="admin-box"><h3>Bookings by service type</h3><div id="cCat"></div></div>
            <div class="admin-box"><h3>Jobs per technician</h3><div id="cTech"></div></div>
            <div class="admin-box"><h3>Check-ins, last 7 days</h3><div id="cAtt"></div></div>
        </div>
        <p><button class="btn-sm btn-ghost" id="csv" style="margin-top:0">Export bookings (CSV)</button></p>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const me = P.init({ roles: ["admin"] });
        if (me) {
            const bk = P.bookings(), pays = P.get("ssPayments", []), now = new Date();
            const k = function (v) { return v >= 1000 ? (v / 1000).toFixed(v % 1000 ? 1 : 0) + "k" : String(v); };
            const months = [];
            for (let i = 5; i >= 0; i--) { const d = new Date(now.getFullYear(), now.getMonth() - i, 1); months.push({ key: d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, "0"), label: d.toLocaleString("en-PH", { month: "short" }) }); }
            const revenue = pays.reduce(function (s, p) { return s + Number(p.amount); }, 0);

            document.getElementById("tiles").innerHTML =
                '<div class="tile"><b>' + bk.length + "</b><span>Total bookings</span></div>" +
                '<div class="tile"><b>' + bk.filter(function (b) { return b.status === "completed"; }).length + "</b><span>Completed services</span></div>" +
                '<div class="tile"><b>' + P.peso(revenue) + "</b><span>Revenue recorded</span></div>" +
                '<div class="tile"><b>' + P.get("ssQuotes", []).length + "</b><span>Quote requests</span></div>";

            document.getElementById("cMonth").innerHTML = P.columns(months.map(function (m) { return m.label; }), months.map(function (m) { return bk.filter(function (b) { return b.date.slice(0, 7) === m.key; }).length; }));
            document.getElementById("cRev").innerHTML = P.columns(months.map(function (m) { return m.label; }), months.map(function (m) { return pays.filter(function (p) { return p.date.slice(0, 7) === m.key; }).reduce(function (s, p) { return s + Number(p.amount); }, 0); }), k);

            function tally(items, keyFn) { const o = {}; items.forEach(function (i) { const x = keyFn(i); o[x] = (o[x] || 0) + 1; }); return Object.keys(o).map(function (x) { return [x, o[x]]; }).sort(function (a, b) { return b[1] - a[1]; }); }
            document.getElementById("cStatus").innerHTML = P.hbars(tally(bk, function (b) { return b.status.charAt(0).toUpperCase() + b.status.slice(1); }));
            document.getElementById("cCat").innerHTML = P.hbars(tally(bk, function (b) { return b.category; }));
            document.getElementById("cTech").innerHTML = P.hbars(tally(bk.filter(function (b) { return b.tech && (b.status === "approved" || b.status === "completed"); }), function (b) { return P.nameOf(b.tech); }));

            const days = [];
            for (let i = 6; i >= 0; i--) { const d = new Date(now.getFullYear(), now.getMonth(), now.getDate() - i); days.push({ key: P.iso(d), label: d.toLocaleString("en-PH", { weekday: "short" }) }); }
            const logs = getTimeLogs();
            document.getElementById("cAtt").innerHTML = P.columns(days.map(function (d) { return d.label; }), days.map(function (d) { return logs.filter(function (l) { return P.logDate(l) === d.key; }).length; }));

            document.getElementById("csv").onclick = function () {
                P.exportCsv("bookings.csv", [["Date", "Time", "Customer", "Service", "Category", "Technician", "Status", "Price"]].concat(bk.sort(P.byWhen).map(function (b) {
                    return [b.date, b.time, b.customerName, b.service, b.category, b.tech ? P.nameOf(b.tech) : "", b.status, b.price];
                })));
            };
        }
    </script>
</body>
</html>
