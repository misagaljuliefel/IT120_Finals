<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Settings</title>
    <link rel="stylesheet" href="SunSonSolar.css" />
    <link rel="stylesheet" href="SunSonSolarPortal.css" />
</head>
<body style="display:none">
    <main class="page" style="max-width:760px">
        <h1>Settings</h1>
        <div class="admin-box">
            <h3>Company details</h3>
            <p class="muted" style="margin-bottom:10px">Shown on receipts and the support page.</p>
            <form id="sForm">
                <label for="sCompany">Company name:</label><input type="text" id="sCompany" required />
                <div class="row">
                    <div><label for="sPhone">Phone:</label><input type="text" id="sPhone" required style="width:100%" /></div>
                    <div><label for="sEmail">Email:</label><input type="email" id="sEmail" required style="width:100%" /></div>
                </div>
                <label for="sAddr">Address:</label><input type="text" id="sAddr" required />
                <h3 style="margin:10px 0">Booking rules</h3>
                <label style="font-weight:normal"><input type="checkbox" id="sOpen" style="margin:0 8px 0 0" /> Customers can book online</label>
                <div class="row" style="margin-top:12px">
                    <div><label for="sStart">Opening time:</label><input type="time" id="sStart" required style="width:100%" /></div>
                    <div><label for="sEnd">Closing time:</label><input type="time" id="sEnd" required style="width:100%" /></div>
                    <div><label for="sMax">Max bookings per day:</label><input type="number" id="sMax" min="1" max="50" required style="width:100%" /></div>
                </div>
                <button type="submit">Save settings</button>
            </form>
        </div>
        <div class="admin-box">
            <h3>Data</h3>
            <p class="muted" style="margin-bottom:10px">The site currently stores everything in this browser. Sample data was added so each page has something to show.</p>
            <button class="btn-sm" id="load">Load sample data</button>
            <button class="btn-sm btn-danger" id="wipe">Remove all bookings, quotes, tickets and logs</button>
        </div>
    </main>

    <script src="SunSonSolarData.js"></script>
    <script src="SunSonSolarPortal.js"></script>
    <script>
        const me = P.init({ roles: ["admin"] });
        if (me) {
            const $ = function (id) { return document.getElementById(id); };
            const s = P.settings();
            $("sCompany").value = s.company; $("sPhone").value = s.phone; $("sEmail").value = s.email; $("sAddr").value = s.address;
            $("sOpen").checked = s.bookingOpen; $("sStart").value = s.hoursStart; $("sEnd").value = s.hoursEnd; $("sMax").value = s.maxPerDay;
            $("sForm").addEventListener("submit", function (e) {
                e.preventDefault();
                if ($("sStart").value >= $("sEnd").value) { P.toast("Closing time must be after opening time."); return; }
                const cur = P.get("ssSettings", {});
                cur.company = $("sCompany").value.trim(); cur.phone = $("sPhone").value.trim(); cur.email = $("sEmail").value.trim(); cur.address = $("sAddr").value.trim();
                cur.bookingOpen = $("sOpen").checked; cur.hoursStart = $("sStart").value; cur.hoursEnd = $("sEnd").value; cur.maxPerDay = Number($("sMax").value);
                P.set("ssSettings", cur);
                P.toast("Settings saved.");
            });
            $("load").onclick = function () {
                if (!confirm("Replace all current bookings, quotes, tickets and logs with sample data?")) return;
                P.seedDemo(true); P.toast("Sample data loaded.");
            };
            $("wipe").onclick = function () {
                if (!confirm("Delete all bookings, payments, quotes, tickets, reports, notifications and check-in logs? Accounts are kept.")) return;
                P.clearData(); P.toast("All activity data removed.");
            };
        }
    </script>
</body>
</html>
