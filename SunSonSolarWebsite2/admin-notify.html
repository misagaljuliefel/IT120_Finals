<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Notification Control</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page" style="max-width:900px">
        <h1>Notification Control</h1>
        <div class="admin-box">
            <h3>Send a notification</h3>
            <form id="nForm">
                <label for="aud">Send to:</label>
                <select id="aud">
                    <option value="customer">All customers</option><option value="technician">All technicians</option>
                    <option value="admin">All admin staff</option><option value="all">Everyone</option><option value="one">One person...</option>
                </select>
                <select id="one" style="display:none"></select>
                <label for="nTitle">Title:</label>
                <input type="text" id="nTitle" required maxlength="80" />
                <label for="nBody">Message:</label>
                <textarea id="nBody" required maxlength="500"></textarea>
                <label id="annRow" style="font-weight:normal"><input type="checkbox" id="ann" style="margin:0 8px 0 0" /> Also post on the technician Announcements page</label>
                <button type="submit">Send notification</button>
            </form>
        </div>
        <div class="admin-box">
            <h3>Automatic notifications</h3>
            <p class="muted" style="margin-bottom:10px">Choose which events notify people automatically.</p>
            <div id="rules"></div>
            <button class="btn-sm" id="saveRules" style="margin-top:12px">Save</button>
        </div>
        <div class="admin-box">
            <h3>Sent messages</h3>
            <div class="tbl-wrap"><table>
                <thead><tr><th>Sent</th><th>To</th><th>Title</th><th>People</th></tr></thead>
                <tbody id="log"></tbody>
            </table></div>
        </div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const me = P.init({ roles: ["admin"] });
        if (me) {
            const RULES = [
                ["newBooking", "Tell admins when a customer books or asks to reschedule"], ["approve", "Tell the customer when a booking is approved"],
                ["decline", "Tell the customer when a booking is declined"], ["assign", "Tell a technician when a job is assigned"],
                ["reschedule", "Tell customer and technician when a booking is moved"], ["complete", "Tell the customer when a service is completed and the receipt is ready"]
            ];
            const aud = document.getElementById("aud"), one = document.getElementById("one");
            one.innerHTML = getAccounts().filter(function (a) { return a.active !== false; }).map(function (a) { return '<option value="' + P.esc(a.uname) + '">' + P.esc(P.fullName(a) + " (" + roleOf(a) + ")") + "</option>"; }).join("");
            function sync() {
                one.style.display = aud.value === "one" ? "block" : "none";
                document.getElementById("annRow").style.display = aud.value === "technician" || aud.value === "all" ? "block" : "none";
            }
            aud.onchange = sync; sync();

            function drawLog() {
                document.getElementById("log").innerHTML = P.get("ssNotifLog", []).map(function (l) {
                    return "<tr><td>" + P.fmtStamp(l.ts) + "</td><td>" + P.esc(l.audience) + "</td><td>" + P.esc(l.title) + "</td><td>" + l.count + "</td></tr>";
                }).join("") || '<tr><td colspan="4" class="empty">Nothing sent yet.</td></tr>';
            }
            document.getElementById("nForm").addEventListener("submit", function (e) {
                e.preventDefault();
                const title = document.getElementById("nTitle").value.trim(), body = document.getElementById("nBody").value.trim();
                const who = getAccounts().filter(function (a) {
                    if (a.active === false) return false;
                    return aud.value === "all" || (aud.value === "one" ? a.uname === one.value : roleOf(a) === aud.value);
                });
                if (!who.length) { P.toast("No one to send to."); return; }
                who.forEach(function (a) { P.notify(a.uname, title, body); });
                if (document.getElementById("ann").checked && document.getElementById("annRow").style.display !== "none") {
                    const all = P.get("ssAnnouncements", []);
                    all.unshift({ id: P.uid("a"), title: title, body: body, ts: new Date().toISOString(), by: me.uname });
                    P.set("ssAnnouncements", all);
                }
                const log = P.get("ssNotifLog", []);
                log.unshift({ id: P.uid("l"), audience: aud.options[aud.selectedIndex].text.replace("...", "") + (aud.value === "one" ? one.options[one.selectedIndex].text : ""), title: title, count: who.length, ts: new Date().toISOString() });
                P.set("ssNotifLog", log);
                this.reset(); sync(); drawLog();
                P.toast("Sent to " + who.length + (who.length === 1 ? " person." : " people."));
            });

            const cur = P.settings().notify;
            document.getElementById("rules").innerHTML = RULES.map(function (r) {
                return '<label style="font-weight:normal;margin-bottom:8px"><input type="checkbox" data-rule="' + r[0] + '"' + (cur[r[0]] ? " checked" : "") + ' style="margin:0 8px 0 0" /> ' + P.esc(r[1]) + "</label>";
            }).join("");
            document.getElementById("saveRules").onclick = function () {
                const s = P.get("ssSettings", {});
                s.notify = {};
                document.querySelectorAll("[data-rule]").forEach(function (c) { s.notify[c.dataset.rule] = c.checked; });
                P.set("ssSettings", s);
                P.toast("Automatic notification settings saved.");
            };
            drawLog();
        }
    </script>
</body>
</html>
