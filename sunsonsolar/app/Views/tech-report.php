<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Report a Problem<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="page" style="max-width:640px">
        <h1>Report a Problem</h1>
        <div class="admin-box">
            <form id="rForm" class="big-form">
                <label for="rJob">Which job?</label>
                <select id="rJob"></select>
                <label for="rType">What kind of problem?</label>
                <select id="rType"><option>Equipment</option><option>Safety</option><option>Customer access</option><option>Running late</option><option>Other</option></select>
                <label for="rText">What happened?</label>
                <textarea id="rText" required maxlength="600"></textarea>
                <button type="submit">Send report</button>
            </form>
        </div>
        <div class="admin-box"><h3>My reports</h3><div id="mine"></div></div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const user = P.init({ roles: ["technician"] });
        if (user) {
            const jobs = P.bookings().filter(function (b) { return b.tech === user.uname && b.status === "approved"; }).sort(P.byWhen);
            document.getElementById("rJob").innerHTML = '<option value="">Not about a specific job</option>' + jobs.map(function (b) {
                return '<option value="' + P.esc(b.id) + '">' + P.esc(b.customerName + " - " + b.service) + "</option>";
            }).join("");

            function draw() {
                const mine = P.get("ssReports", []).filter(function (r) { return r.tech === user.uname; });
                document.getElementById("mine").innerHTML = mine.length ? mine.map(function (r) {
                    return '<div class="note-card"><h4>' + P.esc(r.type) + " " + P.chip(r.status) + "</h4><p>" + P.esc(r.details) + "</p>" +
                        (r.note ? '<p><strong>Office reply:</strong> ' + P.esc(r.note) + "</p>" : "") + '<span class="when">' + P.fmtStamp(r.ts) + "</span></div>";
                }).join("") : '<p class="empty">You have not sent any reports.</p>';
            }
            document.getElementById("rForm").addEventListener("submit", function (e) {
                e.preventDefault();
                const sel = document.getElementById("rJob");
                const all = P.get("ssReports", []);
                all.unshift({
                    id: P.uid("r"), tech: user.uname, techName: P.fullName(user), jobId: sel.value, jobLabel: sel.options[sel.selectedIndex].text,
                    type: document.getElementById("rType").value, details: document.getElementById("rText").value.trim(), ts: new Date().toISOString(), status: "open", note: ""
                });
                P.set("ssReports", all);
                P.notifyRole("admin", "Problem report from " + P.fullName(user), all[0].type + ": " + all[0].details.slice(0, 80));
                this.reset();
                P.toast("Report sent. The office has been notified.");
                draw();
            });
            draw();
        }
    </script>
<?= $this->endSection() ?>