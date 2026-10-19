<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Request a Free Quote</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
    <link rel="stylesheet"    href="<?= base_url('assets/SunSonSolarPortal.css') ?>" />
</head>
<body style="display:none">
    <div class="container wide" id="quoteBox">
        <h1>Sun Son Solar</h1>
        <h2>Request a Free Quote</h2>
        <p class="muted" style="margin-bottom:14px">Tell us how to reach you and what you need. We reply within one business day.</p>
        <form id="quoteForm">
            <label for="qName">Full name:</label>
            <input type="text" id="qName" required />
            <div class="row">
                <div><label for="qEmail">Email:</label><input type="email" id="qEmail" required style="width:100%" /></div>
                <div><label for="qPhone">Phone:</label><input type="tel" id="qPhone" placeholder="09123456789" required style="width:100%" /></div>
            </div>
            <label for="qService">What do you need?</label>
            <select id="qService" required>
                <option value="" disabled selected>Select a service</option>
                <option>Free Site Consultation</option>
                <option>Residential Solar Installation</option>
                <option>Commercial System Proposal</option>
                <option>System Maintenance &amp; Inspection</option>
                <option>Products only</option>
            </select>
            <div id="qItemsWrap" style="display:none">
                <label>Items from your quote list:</label>
                <ul id="qItems" style="margin:0 0 12px 18px;font-size:14px"></ul>
            </div>
            <label for="qNotes">Anything else? (optional)</label>
            <textarea id="qNotes" maxlength="500"></textarea>
            <button type="submit">Send quote request</button>
        </form>
    </div>

    <script src="<?= base_url('assets/SunSonSolarData.js') ?>"></script>
    <script src="<?= base_url('assets/SunSonSolarPortal.js') ?>"></script>
    <script>
        const user = P.init({ public: true });
        if (user) {
            document.getElementById("qName").value = P.fullName(user);
            document.getElementById("qEmail").value = user.email || "";
            document.getElementById("qPhone").value = user.phone || "";
        }
        const items = getQuoteList();
        if (items.length) {
            document.getElementById("qItemsWrap").style.display = "block";
            document.getElementById("qItems").innerHTML = items.map(function (i) { return "<li>" + P.esc(i.name) + "</li>"; }).join("");
        }

        document.getElementById("quoteForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const phone = document.getElementById("qPhone").value.trim();
            if (!/^09[0-9]{9}$/.test(phone)) { P.toast("Phone number must look like 09123456789."); return; }
            const id = P.uid("q");
            const all = P.get("ssQuotes", []);
            all.unshift({
                id: id, name: document.getElementById("qName").value.trim(), email: document.getElementById("qEmail").value.trim(), phone: phone,
                service: document.getElementById("qService").value, items: items.map(function (i) { return i.name; }),
                notes: document.getElementById("qNotes").value.trim(), ts: new Date().toISOString(), status: "new", customer: user ? user.uname : ""
            });
            P.set("ssQuotes", all);
            clearQuoteList();
            document.getElementById("quoteBox").innerHTML = '<h2 style="margin-bottom:10px">Thank you!</h2><p>We received your request and will contact you within one business day.</p>' +
                '<p class="muted" style="margin:12px 0">Reference: ' + P.esc(id.toUpperCase()) + '</p><p><a class="btn-link" href="' + (user ? homeFor(user) : "<?= base_url('SunSonSolarLanding') ?>") + '">Back to ' + (user ? "my dashboard" : "home") + "</a></p>";
        });
    </script>
</body>
</html>
