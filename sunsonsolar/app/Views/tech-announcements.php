<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Announcements</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
    <link rel="stylesheet"    href="<?= base_url('assets/SunSonSolarPortal.css') ?>" />
</head>
<body style="display:none">
    <main class="page" style="max-width:720px">
        <h1>Announcements</h1>
        <div class="admin-box" id="list"></div>
    </main>

    <script src="<?= base_url('assets/SunSonSolarData.js') ?>"></script>
    <script src="<?= base_url('assets/SunSonSolarPortal.js') ?>"></script>
    <script>
        const user = P.init({ roles: ["technician"] });
        if (user) {
            const ann = P.get("ssAnnouncements", []);
            document.getElementById("list").innerHTML = ann.length ? ann.map(function (a) {
                return '<div class="note-card"><h4 style="font-size:20px">' + P.esc(a.title) + '</h4><p style="font-size:17px">' + P.esc(a.body) + '</p><span class="when">' + P.fmtStamp(a.ts) + "</span></div>";
            }).join("") : '<p class="empty">No announcements yet.</p>';
        }
    </script>
</body>
</html>
