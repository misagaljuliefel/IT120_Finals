<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Attendance</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page" style="max-width:1000px">
        <h1>Attendance</h1>
        <div class="admin-box">
            <div class="toolbar">
                <label for="day" style="margin:0">Date:</label><input type="date" id="day" />
                <input type="search" id="q" class="grow" placeholder="Search by name..." aria-label="Search by name" />
                <button class="btn-sm btn-ghost" id="csv">Export CSV</button>
            </div>
            <p class="muted" id="summary" style="margin-bottom:10px"></p>
            <div class="tbl-wrap"><table>
                <thead><tr><th>Name</th><th>Department</th><th>Checked in</th><th>Location</th></tr></thead>
                <tbody id="rows"></tbody>
            </table></div>
        </div>
        <div class="admin-box"><h3>Not checked in yet</h3><p id="absent"></p></div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const me = P.init({ roles: ["admin"] });
        if (me) {
            const dayEl = document.getElementById("day");
            dayEl.value = P.today();
            function logs() {
                const q = document.getElementById("q").value.trim().toLowerCase();
                return getTimeLogs().filter(function (l) { return P.logDate(l) === dayEl.value && (!q || l.name.toLowerCase().indexOf(q) !== -1); });
            }
            function draw() {
                const l = logs();
                document.getElementById("rows").innerHTML = l.map(function (x) {
                    const a = findAccount(x.uname);
                    const when = x.ts ? new Date(x.ts).toLocaleTimeString("en-PH", { hour: "numeric", minute: "2-digit" }) : x.time;
                    return "<tr><td>" + P.esc(x.name) + "</td><td>" + P.esc(a ? a.department : "-") + "</td><td>" + P.esc(when) + "</td><td>" +
                        (x.lat ? '<a target="_blank" rel="noopener" href="https://www.google.com/maps?q=' + encodeURIComponent(x.lat + "," + x.lng) + '">' + P.esc(x.lat + ", " + x.lng) + "</a>" : '<span class="muted">Not shared</span>') + "</td></tr>";
                }).join("") || '<tr><td colspan="4" class="empty">No check-ins on this date.</td></tr>';
                const staff = getAccounts().filter(function (a) { return roleOf(a) !== "customer" && a.active !== false; });
                const inIds = getTimeLogs().filter(function (x) { return P.logDate(x) === dayEl.value; }).map(function (x) { return x.uname; });
                const away = staff.filter(function (a) { return inIds.indexOf(a.uname) === -1; });
                document.getElementById("summary").textContent = (staff.length - away.length) + " of " + staff.length + " employees checked in.";
                document.getElementById("absent").textContent = away.length ? away.map(P.fullName).join(", ") : "Everyone has checked in.";
            }
            dayEl.onchange = draw;
            document.getElementById("q").oninput = draw;
            document.getElementById("csv").onclick = function () {
                P.exportCsv("attendance-" + dayEl.value + ".csv", [["Name", "Date", "Time", "Latitude", "Longitude"]].concat(logs().map(function (x) { return [x.name, P.logDate(x), x.ts || x.time, x.lat, x.lng]; })));
            };
            draw();
        }
    </script>
</body>
</html>
