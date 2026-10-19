<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Inbox</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page" style="max-width:1000px">
        <h1>Inbox</h1>
        <div class="toolbar">
            <button class="tab-btn active" data-tab="reports">Problem reports</button>
            <button class="tab-btn" data-tab="tickets">Customer tickets</button>
        </div>
        <div class="admin-box" id="box"></div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const me = P.init({ roles: ["admin"] });
        if (me) {
            let tab = "reports";
            function reports() {
                const all = P.get("ssReports", []);
                return all.length ? all.map(function (r) {
                    return '<div class="note-card"><h4>' + P.esc(r.type) + " from " + P.esc(r.techName) + " " + P.chip(r.status) + "</h4><p>" + P.esc(r.details) + "</p>" +
                        '<p class="muted">Job: ' + P.esc(r.jobLabel || "-") + " | " + P.fmtStamp(r.ts) + "</p>" + (r.note ? "<p><strong>Reply:</strong> " + P.esc(r.note) + "</p>" : "") +
                        (r.status === "open" ? '<form class="toolbar" data-r="' + P.esc(r.id) + '" style="margin-top:8px"><input class="grow" type="text" placeholder="Reply to technician (optional)" maxlength="200" /><button class="btn-sm" type="submit">Mark resolved</button></form>' :
                            '<button class="btn-sm btn-ghost" data-reopen="' + P.esc(r.id) + '">Reopen</button>') + "</div>";
                }).join("") : '<p class="empty">No problem reports. Technician reports will appear here.</p>';
            }
            function tickets() {
                const all = P.get("ssTickets", []);
                return all.length ? all.map(function (t) {
                    return '<details class="thread"' + (t.status === "open" ? " open" : "") + "><summary><strong>" + P.esc(t.subject) + "</strong> from " + P.esc(t.name) + " " + P.chip(t.status) + ' <span class="muted">' + P.fmtStamp(t.ts) + "</span></summary>" +
                        '<p style="margin-top:8px;white-space:pre-line">' + P.esc(t.message) + "</p>" +
                        t.replies.map(function (r) { return '<div class="reply' + (r.staff ? " staff" : "") + '"><strong>' + (r.staff ? "Sun Son Solar" : P.esc(t.name)) + '</strong> <span class="muted">' + P.fmtStamp(r.ts) + '</span><br><span style="white-space:pre-line">' + P.esc(r.text) + "</span></div>"; }).join("") +
                        (t.status === "closed" ? '<button class="btn-sm btn-ghost" data-tre="' + P.esc(t.id) + '">Reopen</button>' :
                            '<form data-t="' + P.esc(t.id) + '"><textarea placeholder="Write a reply..." required maxlength="800"></textarea><button class="btn-sm" type="submit">Send reply</button> <button class="btn-sm btn-ghost" type="button" data-close="' + P.esc(t.id) + '">Close ticket</button></form>') + "</details>";
                }).join("") : '<p class="empty">No customer tickets yet.</p>';
            }
            function draw() { document.getElementById("box").innerHTML = tab === "reports" ? reports() : tickets(); }
            function patchTicket(id, fn) { const all = P.get("ssTickets", []); const t = all.find(function (x) { return x.id === id; }); fn(t); P.set("ssTickets", all); return t; }

            document.querySelector(".toolbar").addEventListener("click", function (e) {
                const b = e.target.closest("[data-tab]");
                if (!b) return;
                document.querySelectorAll(".tab-btn").forEach(function (x) { x.classList.remove("active"); });
                b.classList.add("active"); tab = b.dataset.tab; draw();
            });
            document.getElementById("box").addEventListener("submit", function (e) {
                e.preventDefault();
                const f = e.target;
                if (f.dataset.r) {
                    const all = P.get("ssReports", []);
                    const r = all.find(function (x) { return x.id === f.dataset.r; });
                    r.status = "resolved"; r.note = f.querySelector("input").value.trim();
                    P.set("ssReports", all);
                    P.notify(r.tech, "Problem report resolved", r.note || "The office marked your report as resolved.");
                }
                if (f.dataset.t) {
                    const text = f.querySelector("textarea").value.trim();
                    const t = patchTicket(f.dataset.t, function (x) { x.replies.push({ staff: true, text: text, ts: new Date().toISOString() }); x.status = "answered"; });
                    P.notify(t.customer, "Reply to your support ticket", t.subject);
                    P.toast("Reply sent.");
                }
                draw();
            });
            document.getElementById("box").addEventListener("click", function (e) {
                const t = e.target.closest("button");
                if (!t) return;
                if (t.dataset.reopen) { const all = P.get("ssReports", []); all.find(function (x) { return x.id === t.dataset.reopen; }).status = "open"; P.set("ssReports", all); }
                if (t.dataset.close) patchTicket(t.dataset.close, function (x) { x.status = "closed"; });
                if (t.dataset.tre) patchTicket(t.dataset.tre, function (x) { x.status = "open"; });
                if (t.dataset.reopen || t.dataset.close || t.dataset.tre) draw();
            });
            draw();
        }
    </script>
</body>
</html>
