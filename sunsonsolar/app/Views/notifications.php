<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Notifications</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page" style="max-width:760px">
        <h1>Notifications</h1>
        <div class="admin-box">
            <div class="toolbar"><span class="grow muted" id="sub"></span><button class="btn-sm btn-ghost" id="clearAll">Clear all</button></div>
            <div id="list"></div>
        </div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const user = P.init({});
        if (user) {
            const all = P.get("ssNotifs", []);
            const fresh = {};
            all.forEach(function (n) { if (n.to === user.uname && !n.read) { fresh[n.id] = true; n.read = true; } });
            P.set("ssNotifs", all);
            const bell = document.querySelector(".bell .count");
            if (bell) bell.remove();

            function draw() {
                const mine = P.get("ssNotifs", []).filter(function (n) { return n.to === user.uname; });
                document.getElementById("sub").textContent = mine.length + " notification" + (mine.length === 1 ? "" : "s");
                document.getElementById("list").innerHTML = mine.length ? mine.map(function (n) {
                    return '<div class="note-card' + (fresh[n.id] ? " unread" : "") + '"><h4>' + P.esc(n.title) + "</h4><p>" + P.esc(n.body) + '</p><span class="when">' + P.fmtStamp(n.ts) + "</span></div>";
                }).join("") : '<p class="empty">You are all caught up. New updates will show up here.</p>';
            }
            document.getElementById("clearAll").addEventListener("click", function () {
                if (!confirm("Remove all your notifications?")) return;
                P.set("ssNotifs", P.get("ssNotifs", []).filter(function (n) { return n.to !== user.uname; }));
                draw();
            });
            draw();
        }
    </script>
</body>
</html>
