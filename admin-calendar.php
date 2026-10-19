<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Bookings Calendar<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="page" style="max-width:1250px">
        <h1>Bookings</h1>
        <div class="admin-box">
            <div class="cal-head">
                <button class="btn-sm btn-ghost" id="prev" aria-label="Previous month">&larr; Prev</button>
                <h3 id="monthName"></h3>
                <button class="btn-sm btn-ghost" id="todayBtn">Today</button>
                <button class="btn-sm btn-ghost" id="next" aria-label="Next month">Next &rarr;</button>
            </div>
            <div class="cal-grid" id="cal"></div>
        </div>
        <div class="admin-box">
            <div class="toolbar">
                <h3 class="grow" id="listTitle" style="margin:0"></h3>
                <select id="statusFilter" aria-label="Filter by status">
                    <option value="pending">Waiting for approval</option><option value="approved">Approved</option><option value="completed">Completed</option>
                    <option value="declined">Declined</option><option value="cancelled">Cancelled</option><option value="all">All bookings</option>
                </select>
                <button class="btn-sm btn-ghost" id="clearDay" style="display:none">Show all days</button>
            </div>
            <div class="tbl-wrap"><table>
                <thead><tr><th>Customer</th><th>Service</th><th>When</th><th>Address</th><th>Technician</th><th>Status</th><th>Manage</th></tr></thead>
                <tbody id="rows"></tbody>
            </table></div>
        </div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const me = P.init({ roles: ["admin"] });
        if (me) {
            let month = new Date(); month.setDate(1);
            let selDay = "";
            const techs = P.technicians();

            function drawCal() {
                const y = month.getFullYear(), m = month.getMonth(), first = new Date(y, m, 1).getDay(), days = new Date(y, m + 1, 0).getDate();
                document.getElementById("monthName").textContent = month.toLocaleString("en-PH", { month: "long", year: "numeric" });
                const all = P.bookings();
                let h = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].map(function (d) { return '<div class="cal-dow">' + d + "</div>"; }).join("");
                for (let i = 0; i < first; i++) h += '<div class="cal-day blank"></div>';
                for (let d = 1; d <= days; d++) {
                    const key = y + "-" + String(m + 1).padStart(2, "0") + "-" + String(d).padStart(2, "0");
                    const on = all.filter(function (b) { return b.date === key; });
                    const c = function (s) { return on.filter(function (b) { return b.status === s; }).length; };
                    h += '<button type="button" class="cal-day' + (key === P.today() ? " today" : "") + (key === selDay ? " sel" : "") + '" data-day="' + key + '"><b>' + d + "</b>" +
                        (c("pending") ? '<span class="cal-dot pending">' + c("pending") + " pending</span>" : "") +
                        (c("approved") ? '<span class="cal-dot">' + c("approved") + " approved</span>" : "") +
                        (c("completed") ? '<span class="cal-dot completed">' + c("completed") + " done</span>" : "") + "</button>";
                }
                document.getElementById("cal").innerHTML = h;
            }

            function drawList() {
                const f = document.getElementById("statusFilter").value;
                const all = P.bookings().filter(function (b) { return selDay ? b.date === selDay : (f === "all" || b.status === f); }).sort(P.byWhen);
                document.getElementById("listTitle").textContent = selDay ? "Bookings on " + P.fmtDate(selDay) : "Bookings";
                document.getElementById("clearDay").style.display = selDay ? "inline-block" : "none";
                document.getElementById("statusFilter").style.display = selDay ? "none" : "inline-block";
                document.getElementById("rows").innerHTML = all.map(function (b) {
                    const c = findAccount(b.customer);
                    const open = b.status === "pending" || b.status === "approved";
                    const sel = open ? '<select data-tech="' + P.esc(b.id) + '"><option value="">Unassigned</option>' + techs.map(function (t) {
                        return '<option value="' + P.esc(t.uname) + '"' + (t.uname === b.tech ? " selected" : "") + ">" + P.esc(P.fullName(t)) + "</option>";
                    }).join("") + "</select>" : P.esc(b.tech ? P.nameOf(b.tech) : "-");
                    const acts = (b.status === "pending" ? '<button class="btn-sm" data-act="approve" data-id="' + P.esc(b.id) + '">Approve</button><button class="btn-sm btn-danger" data-act="decline" data-id="' + P.esc(b.id) + '">Decline</button>' : "") +
                        (open ? '<button class="btn-sm btn-ghost" data-act="move" data-id="' + P.esc(b.id) + '">Reschedule</button>' : "") +
                        (b.status === "approved" ? '<button class="btn-sm" data-act="done" data-id="' + P.esc(b.id) + '">Mark completed</button>' : "");
                    return "<tr><td>" + P.esc(b.customerName) + (c ? '<br><span class="muted">' + P.esc(c.phone) + "</span>" : "") + "</td><td>" + P.esc(b.service) + (b.notes ? '<br><span class="muted">' + P.esc(b.notes) + "</span>" : "") + "</td><td>" +
                        P.fmtDate(b.date) + "<br>" + P.fmtTime(b.time) + "</td><td>" + P.esc(b.address) + "</td><td>" + sel + "</td><td>" + P.chip(b.status) + "</td><td>" + (acts || "-") + "</td></tr>";
                }).join("") || '<tr><td colspan="7" class="empty">No bookings here.</td></tr>';
            }
            function redraw() { drawCal(); drawList(); }

            function approve(b) {
                if (!b.tech) { P.toast("Assign a technician first, then approve."); return; }
                const clash = P.bookings().some(function (x) { return x.id !== b.id && x.tech === b.tech && x.date === b.date && x.time === b.time && x.status === "approved"; });
                if (clash && !confirm(P.nameOf(b.tech) + " already has a job at that time. Approve anyway?")) return;
                P.updateBooking(b.id, function (x) { x.status = "approved"; });
                P.notifyIf("approve", b.customer, "Service approved", b.service + " is confirmed for " + P.fmtDate(b.date) + " at " + P.fmtTime(b.time) + ".");
                P.notifyIf("assign", b.tech, "New job assigned", b.service + " for " + b.customerName + " on " + P.fmtDate(b.date) + ".");
                P.toast("Booking approved.");
            }

            document.getElementById("rows").addEventListener("click", function (e) {
                const t = e.target.closest("[data-act]");
                if (!t) return;
                const b = P.bookings().find(function (x) { return x.id === t.dataset.id; });
                if (t.dataset.act === "approve") approve(b);
                if (t.dataset.act === "decline") {
                    const why = prompt("Reason for declining (the customer will see this):", "");
                    if (why === null) return;
                    P.updateBooking(b.id, function (x) { x.status = "declined"; });
                    P.notifyIf("decline", b.customer, "Service request declined", b.service + " on " + P.fmtDate(b.date) + " could not be scheduled." + (why ? " Reason: " + why : ""));
                    P.toast("Booking declined.");
                }
                if (t.dataset.act === "move") {
                    P.reschedule(b, true, function (d, tm) {
                        P.updateBooking(b.id, function (x) { x.date = d; x.time = tm; });
                        P.notifyIf("reschedule", b.customer, "Service rescheduled", b.service + " moved to " + P.fmtDate(d) + " at " + P.fmtTime(tm) + ".");
                        if (b.tech) P.notifyIf("reschedule", b.tech, "Job rescheduled", b.service + " moved to " + P.fmtDate(d) + " at " + P.fmtTime(tm) + ".");
                        P.toast("Booking rescheduled.");
                        redraw();
                    });
                    return;
                }
                if (t.dataset.act === "done") {
                    const amt = prompt("Amount received (PHP):", b.price);
                    if (amt === null) return;
                    const n = Number(amt);
                    if (isNaN(n) || n < 0) { P.toast("Enter a valid amount."); return; }
                    P.updateBooking(b.id, function (x) { x.status = "completed"; });
                    const rec = P.recordPayment(b, n);
                    P.notifyIf("complete", b.customer, "Service completed", b.service + " is done. Your receipt " + rec.receipt + " is in History & Receipts.");
                    P.toast("Marked completed. Receipt " + rec.receipt + " created.");
                }
                redraw();
            });
            document.getElementById("rows").addEventListener("change", function (e) {
                const s = e.target.closest("[data-tech]");
                if (!s) return;
                const b = P.updateBooking(s.dataset.tech, function (x) { x.tech = s.value; });
                if (s.value && b.status === "approved") P.notifyIf("assign", s.value, "New job assigned", b.service + " for " + b.customerName + " on " + P.fmtDate(b.date) + ".");
                P.toast(s.value ? "Technician assigned." : "Technician removed.");
            });
            document.getElementById("cal").addEventListener("click", function (e) {
                const d = e.target.closest("[data-day]");
                if (!d) return;
                selDay = selDay === d.dataset.day ? "" : d.dataset.day;
                redraw();
            });
            document.getElementById("clearDay").onclick = function () { selDay = ""; redraw(); };
            document.getElementById("statusFilter").onchange = drawList;
            document.getElementById("prev").onclick = function () { month.setMonth(month.getMonth() - 1); drawCal(); };
            document.getElementById("next").onclick = function () { month.setMonth(month.getMonth() + 1); drawCal(); };
            document.getElementById("todayBtn").onclick = function () { month = new Date(); month.setDate(1); drawCal(); };
            redraw();
        }
    </script>
<?= $this->endSection() ?>