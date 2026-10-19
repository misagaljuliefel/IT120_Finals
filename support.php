<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Help & Support<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="page" style="max-width:860px">
        <h1>Help &amp; Support</h1>
        <p class="lead">Send us a message and we will reply here. For urgent issues, call <span id="phone"></span>.</p>
        <div class="admin-box">
            <h3>New support ticket</h3>
            <form id="tForm">
                <label for="tSubject">Subject:</label>
                <input type="text" id="tSubject" required maxlength="100" />
                <label for="tMsg">How can we help?</label>
                <textarea id="tMsg" required maxlength="800"></textarea>
                <button type="submit">Send ticket</button>
            </form>
        </div>
        <div class="admin-box">
            <h3>My tickets</h3>
            <div id="tickets"></div>
        </div>
        <p class="lead"><a href="<?= base_url('SunSonSolarLanding') ?>#help-center" style="color:#fff">Read the FAQ</a></p>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const user = P.init({ roles: ["customer"] });
        if (user) {
            document.getElementById("phone").textContent = P.settings().phone;

            function draw() {
                const mine = P.get("ssTickets", []).filter(function (t) { return t.customer === user.uname; });
                document.getElementById("tickets").innerHTML = mine.length ? mine.map(function (t) {
                    return '<details class="thread"' + (t.status === "answered" ? " open" : "") + "><summary><strong>" + P.esc(t.subject) + "</strong> " + P.chip(t.status) +
                        ' <span class="muted">' + P.fmtStamp(t.ts) + "</span></summary><p style=\"margin-top:8px;white-space:pre-line\">" + P.esc(t.message) + "</p>" +
                        t.replies.map(function (r) {
                            return '<div class="reply' + (r.staff ? " staff" : "") + '"><strong>' + (r.staff ? "Sun Son Solar" : "You") + '</strong> <span class="muted">' + P.fmtStamp(r.ts) + '</span><br><span style="white-space:pre-line">' + P.esc(r.text) + "</span></div>";
                        }).join("") +
                        (t.status === "closed" ? "" : '<form data-t="' + P.esc(t.id) + '"><textarea placeholder="Write a reply..." required maxlength="800"></textarea><button class="btn-sm" type="submit">Send reply</button></form>') + "</details>";
                }).join("") : '<p class="empty">No tickets yet.</p>';
            }

            document.getElementById("tForm").addEventListener("submit", function (e) {
                e.preventDefault();
                const all = P.get("ssTickets", []);
                all.unshift({ id: P.uid("t"), customer: user.uname, name: P.fullName(user), subject: document.getElementById("tSubject").value.trim(), message: document.getElementById("tMsg").value.trim(), ts: new Date().toISOString(), status: "open", replies: [] });
                P.set("ssTickets", all);
                P.notifyRole("admin", "New support ticket", P.fullName(user) + ": " + all[0].subject);
                this.reset();
                P.toast("Ticket sent. We will reply here.");
                draw();
            });
            document.getElementById("tickets").addEventListener("submit", function (e) {
                const f = e.target.closest("form[data-t]");
                if (!f) return;
                e.preventDefault();
                const all = P.get("ssTickets", []);
                const t = all.find(function (x) { return x.id === f.dataset.t; });
                t.replies.push({ staff: false, text: f.querySelector("textarea").value.trim(), ts: new Date().toISOString() });
                t.status = "open";
                P.set("ssTickets", all);
                P.notifyRole("admin", "Customer replied to a ticket", P.fullName(user) + ": " + t.subject);
                draw();
            });
            draw();
        }
    </script>
<?= $this->endSection() ?>