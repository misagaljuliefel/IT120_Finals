<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Schedule a Service<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container wide">
        <h1>Sun Son Solar</h1>
        <h2>Schedule a Service</h2>
        <p class="notice" id="closedMsg" style="display:none;color:#991b1b;margin-bottom:12px">Online booking is paused right now. Please call us to book.</p>
        <form id="bookForm">
            <label for="bCat">Type of service:</label>
            <select id="bCat"></select>
            <label for="bSvc">Service:</label>
            <select id="bSvc"></select>
            <p class="muted" id="bPrice" style="margin:-8px 0 12px"></p>
            <div class="row">
                <div><label for="bDate">Preferred date:</label><input type="date" id="bDate" required style="width:100%" /></div>
                <div><label for="bTime">Preferred time:</label><select id="bTime" style="width:100%"></select></div>
            </div>
            <label for="bAddr">Service address:</label>
            <input type="text" id="bAddr" required />
            <label for="bNotes">Notes for the technician (optional):</label>
            <textarea id="bNotes" maxlength="400"></textarea>
            <button type="submit" id="bBtn">Request this schedule</button>
        </form>
        <p class="form-note">We will confirm your schedule after review. You can reschedule or cancel from your dashboard.</p>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const user = P.init({ roles: ["customer"] });
        if (user) {
            const svcs = P.services();
            const cats = [];
            svcs.forEach(function (s) { if (cats.indexOf(s.cat) === -1) cats.push(s.cat); });
            const catSel = document.getElementById("bCat"), svcSel = document.getElementById("bSvc");
            const q = new URLSearchParams(location.search);
            const pre = svcs.find(function (s) { return s.id === q.get("service"); });
            catSel.innerHTML = cats.map(function (c) { return '<option value="' + P.esc(c) + '">' + P.esc(c) + "</option>"; }).join("");
            if (pre) catSel.value = pre.cat; else if (cats.indexOf(q.get("cat")) !== -1) catSel.value = q.get("cat");

            function fillServices() {
                svcSel.innerHTML = svcs.filter(function (s) { return s.cat === catSel.value; }).map(function (s) {
                    return '<option value="' + P.esc(s.id) + '">' + P.esc(s.name) + "</option>";
                }).join("");
                if (pre && pre.cat === catSel.value) svcSel.value = pre.id;
                showPrice();
            }
            function showPrice() {
                const s = svcs.find(function (x) { return x.id === svcSel.value; });
                document.getElementById("bPrice").textContent = s ? "Starting at " + P.peso(s.price) + ". The final amount is confirmed after approval." : "";
            }
            catSel.addEventListener("change", fillServices);
            svcSel.addEventListener("change", showPrice);
            fillServices();

            const date = document.getElementById("bDate");
            date.min = P.addDays(1);
            document.getElementById("bTime").innerHTML = P.slotOptions("09:00");
            document.getElementById("bAddr").value = user.address || "";

            if (!P.settings().bookingOpen) {
                document.getElementById("closedMsg").style.display = "block";
                document.getElementById("bBtn").disabled = true;
            }

            document.getElementById("bookForm").addEventListener("submit", function (e) {
                e.preventDefault();
                if (date.value < P.addDays(1)) { P.toast("Please pick a date after today."); return; }
                if (P.dayFull(date.value)) { P.toast("That day is fully booked. Please pick another date."); return; }
                const s = svcs.find(function (x) { return x.id === svcSel.value; });
                const bookings = P.bookings();
                bookings.push({
                    id: P.uid("b"), customer: user.uname, customerName: P.fullName(user), service: s.name, category: s.cat,
                    date: date.value, time: document.getElementById("bTime").value, address: document.getElementById("bAddr").value.trim(),
                    notes: document.getElementById("bNotes").value.trim(), status: "pending", tech: "", price: s.price, created: new Date().toISOString()
                });
                P.saveBookings(bookings);
                if (P.settings().notify.newBooking) P.notifyRole("admin", "New booking request", P.fullName(user) + " requested " + s.name + " on " + P.fmtDate(date.value) + ".");
                sessionStorage.setItem("ssFlash", "Request sent. We will confirm your schedule soon.");
                window.location.href = "<?= base_url('customer') ?>";
            });
        }
    </script>
<?= $this->endSection() ?>