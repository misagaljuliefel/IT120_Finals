<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Announcements<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="page" style="max-width:720px">
        <h1>Announcements</h1>
        <div class="admin-box" id="list"></div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const user = P.init({ roles: ["technician"] });
        if (user) {
            const ann = P.get("ssAnnouncements", []);
            document.getElementById("list").innerHTML = ann.length ? ann.map(function (a) {
                return '<div class="note-card"><h4 style="font-size:20px">' + P.esc(a.title) + '</h4><p style="font-size:17px">' + P.esc(a.body) + '</p><span class="when">' + P.fmtStamp(a.ts) + "</span></div>";
            }).join("") : '<p class="empty">No announcements yet.</p>';
        }
    </script>
<?= $this->endSection() ?>