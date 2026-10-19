<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - My Schedule</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page">
        <h1 id="hello">My Schedule</h1>
        <p class="lead">Your upcoming services. You can reschedule or cancel before a technician arrives.</p>
        <div class="tiles" id="tiles"></div>
        <div class="admin-box">
            <div class="toolbar"><h3 class="grow" style="margin:0">Upcoming services</h3><a class="btn-link" href="schedule.html">Book a service</a></div>
            <div class="tbl-wrap"><table>
                <thead><tr><th>Service</th><th>Date &amp; time</th><th>Address</th><th>Technician</th><th>Status</th><th>Manage</th></tr></thead>
                <tbody id="rows"></tbody>
            </table></div>
        </div>
        <div class="admin-box">
            <div class="toolbar"><h3 class="grow" style="margin:0">Latest notifications</h3><a href="notifications.html">See all</a></div>
            <div id="notes"></div>
        </div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const user = P.init({ roles: ["customer"] });
        if (user) {
            document.getElementById("hello").textContent = "Hello, " + user.fname;

            function draw() {
                const mine = P.bookings().filter(function (b) { return b.customer === user.uname; });
                const active = mine.filter(function (b) { return b.status === "pending" || b.status === "approved"; }).sort(P.byWhen);
                const openTickets = P.get("ssTickets", []).filter(function (t) { return t.customer === user.uname && t.status !== "closed"; }).length;
                document.getElementById("tiles").innerHTML =
                    '<div class="tile"><b>' + active.filter(function (b) { return b.status === "approved"; }).length + "</b><span>Confirmed services</span></div>" +
                    '<div class="tile"><b>' + active.filter(function (b) { return b.status === "pending"; }).length + "</b><span>Waiting for approval</span></div>" +
                    '<a class="tile" href="notifications.html"><b>' + P.unread(user.uname) + "</b><span>Unread notifications</span></a>" +
                    '<a class="tile" href="support.html"><b>' + openTickets + "</b><span>Open support tickets</span></a>";

                document.getElementById("rows").innerHTML = active.length ? active.map(function (b) {
                    return "<tr><td>" + P.esc(b.service) + "</td><td>" + P.fmtDate(b.date) + "<br>" + P.fmtTime(b.time) + "</td><td>" + P.esc(b.address) + "</td><td>" +
                        (b.tech ? P.esc(P.nameOf(b.tech)) : '<span class="muted">Not assigned yet</span>') + "</td><td>" + P.chip(b.status) + "</td>" +
                        '<td><button class="btn-sm" data-re="' + P.esc(b.id) + '">Reschedule</button><button class="btn-sm btn-danger" data-cancel="' + P.esc(b.id) + '">Cancel</button></td></tr>';
                }).join("") : '<tr><td colspan="6" class="empty">Nothing scheduled yet. Book your first service to get started.</td></tr>';

                const notes = P.get("ssNotifs", []).filter(function (n) { return n.to === user.uname; }).slice(0, 3);
                document.getElementById("notes").innerHTML = notes.length ? notes.map(function (n) {
                    return '<div class="note-card' + (n.read ? "" : " unread") + '"><h4>' + P.esc(n.title) + "</h4><p>" + P.esc(n.body) + '</p><span class="when">' + P.fmtStamp(n.ts) + "</span></div>";
                }).join("") : '<p class="empty">No notifications yet.</p>';
            }

            document.getElementById("rows").addEventListener("click", function (e) {
                const re = e.target.closest("[data-re]"), ca = e.target.closest("[data-cancel]");
                if (re) {
                    const b = P.bookings().find(function (x) { return x.id === re.dataset.re; });
                    P.reschedule(b, false, function (d, t) {
                        P.updateBooking(b.id, function (x) { x.date = d; x.time = t; x.status = "pending"; });
                        P.notifyRole("admin", "Reschedule requested", P.fullName(user) + " asked to move " + b.service + " to " + P.fmtDate(d) + " " + P.fmtTime(t) + ".");
                        P.toast("New schedule sent for approval.");
                        draw();
                    });
                }
                if (ca && confirm("Cancel this service?")) {
                    const b = P.updateBooking(ca.dataset.cancel, function (x) { x.status = "cancelled"; });
                    P.notifyRole("admin", "Booking cancelled", P.fullName(user) + " cancelled " + b.service + " on " + P.fmtDate(b.date) + ".");
                    if (b.tech) P.notify(b.tech, "Job cancelled", b.service + " on " + P.fmtDate(b.date) + " was cancelled by the customer.");
                    P.toast("Service cancelled.");
                    draw();
                }
            });
            draw();
        }
    </script>
</body>
</html>
