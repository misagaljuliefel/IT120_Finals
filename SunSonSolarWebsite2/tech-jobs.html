<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - My Jobs</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page" style="max-width:720px">
        <h1>My Jobs</h1>
        <div id="jobs"></div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const user = P.init({ roles: ["technician"] });
        if (user) {
            const jobs = P.bookings().filter(function (b) { return b.tech === user.uname && b.status === "approved"; }).sort(P.byWhen);
            function heading(d) {
                if (d === P.today()) return "Today";
                if (d === P.addDays(1)) return "Tomorrow";
                return d < P.today() ? "Earlier - " + P.fmtDate(d) : P.fmtDate(d);
            }
            let html = "", last = "";
            jobs.forEach(function (b) {
                if (b.date !== last) { html += '<h2 class="day-head">' + P.esc(heading(b.date)) + "</h2>"; last = b.date; }
                html += '<div class="job-card"><h3>' + P.esc(b.customerName) + "</h3><p>" + P.esc(b.address) +
                    ' (<a href="https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(b.address) + '" target="_blank" rel="noopener">map</a>)</p><p class="svc">' + P.esc(b.service) + "</p></div>";
            });
            document.getElementById("jobs").innerHTML = html || '<div class="admin-box"><p class="empty">No jobs assigned right now. The office will notify you when one is added.</p></div>';
        }
    </script>
</body>
</html>
