/* Sun Son Solar - shared helpers for customer, technician and admin pages.
   Load AFTER SunSonSolarData.js. */
var P = (function () {
    "use strict";

    var NO_IMG = "data:image/svg+xml;utf8," + encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300"><rect width="100%" height="100%" fill="#f3f4f6"/>' +
        '<text x="50%" y="50%" fill="#9ca3af" font-family="Arial" font-size="20" text-anchor="middle">Image unavailable</text></svg>');

    /* ---------- storage ---------- */
    function get(k, d) {
        try { var v = JSON.parse(localStorage.getItem(k)); return v === null ? d : v; } catch (e) { return d; }
    }
    function set(k, v) { localStorage.setItem(k, JSON.stringify(v)); }
    function uid(p) { return p + "-" + Date.now().toString(36) + Math.random().toString(36).slice(2, 6); }

    /* ---------- formatting ---------- */
    function esc(s) {
        return String(s == null ? "" : s).replace(/[&<>"']/g, function (c) {
            return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
        });
    }
    function pad(n) { return (n < 10 ? "0" : "") + n; }
    function iso(d) { return d.getFullYear() + "-" + pad(d.getMonth() + 1) + "-" + pad(d.getDate()); }
    function today() { return iso(new Date()); }
    function addDays(n) { var d = new Date(); d.setDate(d.getDate() + n); return iso(d); }
    function fmtDate(s) {
        if (!s) return "-";
        var p = s.split("-");
        return new Date(+p[0], +p[1] - 1, +p[2]).toLocaleDateString("en-PH", { year: "numeric", month: "short", day: "numeric" });
    }
    function fmtTime(t) {
        if (!t) return "";
        var p = t.split(":"), h = +p[0];
        return ((h + 11) % 12 + 1) + ":" + p[1] + " " + (h < 12 ? "AM" : "PM");
    }
    function fmtStamp(ts) {
        return ts ? new Date(ts).toLocaleString("en-PH", { month: "short", day: "numeric", hour: "numeric", minute: "2-digit" }) : "-";
    }
    function peso(n) { return "\u20B1" + Number(n || 0).toLocaleString("en-PH"); }
    function page() { return window.location.pathname.split("/").pop() || "index.html"; }
    function fullName(a) { return a ? (a.fname + " " + (a.lname || "")).trim() : ""; }
    function nameOf(uname) { var a = findAccount(uname); return a ? fullName(a) : uname; }

    var STATUS_LABEL = {
        pending: "Pending", approved: "Approved", declined: "Declined", cancelled: "Cancelled", completed: "Completed",
        "new": "New", contacted: "Contacted", closed: "Closed", open: "Open", answered: "Answered", resolved: "Resolved"
    };
    function chip(s) { return '<span class="chip chip-' + esc(s) + '">' + esc(STATUS_LABEL[s] || s) + "</span>"; }

    function toast(msg) {
        var t = document.createElement("div");
        t.className = "toast";
        t.setAttribute("role", "status");
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.remove(); }, 3600);
    }

    function exportCsv(filename, rows) {
        var csv = rows.map(function (r) {
            return r.map(function (c) { return '"' + String(c == null ? "" : c).replace(/"/g, '""') + '"'; }).join(",");
        }).join("\r\n");
        var a = document.createElement("a");
        a.href = URL.createObjectURL(new Blob(["\uFEFF" + csv], { type: "text/csv" }));
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
    }

    /* ---------- validation ---------- */
    function passwordProblem(p) {
        if (p.length < 8) return "Password must be at least 8 characters long.";
        if (!/[A-Z]/.test(p)) return "Password must contain at least one uppercase letter.";
        if (!/[a-z]/.test(p)) return "Password must contain at least one lowercase letter.";
        if (!/[0-9]/.test(p)) return "Password must contain at least one number.";
        if (!/[^A-Za-z0-9]/.test(p)) return "Password must contain at least one special character.";
        return "";
    }
    // Returns the first problem found, or "" when everything is valid.
    function accountProblem(d, opts) {
        opts = opts || {};
        var nameRx = /^[A-Za-z ]+$/;
        if (!nameRx.test(d.fname)) return "First name must use letters only.";
        if (!nameRx.test(d.lname)) return "Last name must use letters only.";
        if (d.mname && !nameRx.test(d.mname)) return "Middle name must use letters only.";
        if (!d.bdate) return "Please enter a birth date.";
        if (d.bdate > today()) return "Birth date cannot be in the future.";
        if (!d.gender) return "Please select a gender.";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(d.email)) return "Please enter a valid email address.";
        if (!/^09[0-9]{9}$/.test(d.phone)) return "Phone number must look like 09123456789.";
        if (!d.address) return "Please enter an address.";
        if (opts.checkUname && !/^[A-Za-z0-9._-]{3,30}$/.test(d.uname)) return "Username must be 3-30 characters: letters, numbers, dots, dashes or underscores.";
        if (opts.password) { var pp = passwordProblem(d.password || ""); if (pp) return pp; }
        return "";
    }

    /* ---------- settings ---------- */
    function settings() {
        var d = {
            company: "Sun Son Solar", phone: "(02) 8123-4567", email: "support@sunsonsolar.com", address: "Pasig City, Metro Manila",
            bookingOpen: true, hoursStart: "08:00", hoursEnd: "17:00", maxPerDay: 6,
            notify: { newBooking: true, approve: true, decline: true, assign: true, reschedule: true, complete: true }
        };
        var s = get("ssSettings", {});
        for (var k in s) {
            if (k === "notify") { for (var j in s.notify) d.notify[j] = s.notify[j]; } else { d[k] = s[k]; }
        }
        return d;
    }

    /* ---------- notifications ---------- */
    function notify(to, title, body) {
        var n = get("ssNotifs", []);
        n.unshift({ id: uid("n"), to: to, title: title, body: body, ts: new Date().toISOString(), read: false });
        set("ssNotifs", n);
    }
    function notifyIf(rule, to, title, body) { if (settings().notify[rule]) notify(to, title, body); }
    function notifyRole(role, title, body) {
        getAccounts().forEach(function (a) { if (roleOf(a) === role && a.active !== false) notify(a.uname, title, body); });
    }
    function unread(uname) { return get("ssNotifs", []).filter(function (n) { return n.to === uname && !n.read; }).length; }

    /* ---------- bookings / payments ---------- */
    function bookings() { return get("ssBookings", []); }
    function saveBookings(b) { set("ssBookings", b); }
    function updateBooking(id, fn) {
        var all = bookings();
        for (var i = 0; i < all.length; i++) {
            if (all[i].id === id) { fn(all[i]); saveBookings(all); return all[i]; }
        }
        return null;
    }
    function byWhen(a, b) { return (a.date + a.time).localeCompare(b.date + b.time); }
    function services() { return CATALOG.filter(function (c) { return c.type === "service"; }); }
    function technicians() { return getAccounts().filter(function (a) { return roleOf(a) === "technician" && a.active !== false; }); }
    function dayFull(date, exceptId) {
        var n = bookings().filter(function (b) {
            return b.date === date && b.id !== exceptId && (b.status === "pending" || b.status === "approved");
        }).length;
        return n >= settings().maxPerDay;
    }
    function slotOptions(sel) {
        var s = settings(), a = parseInt(s.hoursStart, 10), b = parseInt(s.hoursEnd, 10), h = "";
        for (var i = a; i < b; i++) {
            var v = pad(i) + ":00";
            h += '<option value="' + v + '"' + (v === sel ? " selected" : "") + ">" + fmtTime(v) + "</option>";
        }
        return h;
    }
    function recordPayment(b, amount) {
        var p = get("ssPayments", []);
        var rec = {
            id: uid("pay"), receipt: "SSS-" + new Date().getFullYear() + "-" + (1000 + p.length + 1),
            bookingId: b.id, customer: b.customer, service: b.service, amount: amount, date: today(), method: "Recorded by admin"
        };
        p.push(rec);
        set("ssPayments", p);
        return rec;
    }

    // Dialog used by customers (needs a future date) and admins (today is fine).
    function reschedule(b, byAdmin, done) {
        var dlg = document.createElement("dialog");
        dlg.innerHTML = '<form class="dlg-form"><h3>Reschedule service</h3><p class="muted">' + esc(b.service) + "</p>" +
            '<label for="rsDate">New date</label><input type="date" id="rsDate" min="' + (byAdmin ? today() : addDays(1)) + '" value="' + esc(b.date) + '" required>' +
            '<label for="rsTime">New time</label><select id="rsTime">' + slotOptions(b.time) + "</select>" +
            '<div class="dlg-actions"><button type="button" class="btn-ghost" id="rsCancel">Cancel</button><button type="submit">Save new schedule</button></div></form>';
        document.body.appendChild(dlg);
        dlg.addEventListener("close", function () { dlg.remove(); });
        dlg.showModal();
        dlg.querySelector("#rsCancel").onclick = function () { dlg.close(); };
        dlg.querySelector("form").onsubmit = function (e) {
            e.preventDefault();
            var d = dlg.querySelector("#rsDate").value, t = dlg.querySelector("#rsTime").value;
            if (!byAdmin && d <= today()) { toast("Please pick a date after today."); return; }
            if (dayFull(d, b.id)) { toast("That day is fully booked. Please pick another."); return; }
            done(d, t);
            dlg.close();
        };
    }

    /* ---------- check-in widget (technicians and admins) ---------- */
    function logDate(l) {
        if (l.date) return l.date;
        var d = new Date(l.ts || l.time);
        return isNaN(d) ? "" : iso(d);
    }
    function checkin(el, user) {
        function draw() {
            var mine = getTimeLogs().filter(function (l) { return l.uname === user.uname && logDate(l) === today(); });
            if (mine.length) {
                var when = mine[0].ts ? new Date(mine[0].ts).toLocaleTimeString("en-PH", { hour: "numeric", minute: "2-digit" }) : mine[0].time;
                el.innerHTML = '<div class="checkin-done"><div class="check-mark" aria-hidden="true">&#10003;</div><h2>You are checked in</h2><p>' + esc(when) + (mine[0].lat ? "" : " (location not shared)") + "</p></div>";
                return;
            }
            el.innerHTML = '<button type="button" class="checkin-btn" id="ciBtn">CHECK IN</button><p class="muted center">Tap once when you start work.</p>';
            el.querySelector("#ciBtn").onclick = go;
        }
        function save(lat, lng) {
            var now = new Date();
            addTimeLog({ uname: user.uname, name: fullName(user), time: now.toLocaleString(), ts: now.toISOString(), date: iso(now), lat: lat, lng: lng });
            draw();
            toast("Checked in. Have a safe day!");
        }
        function go() {
            var b = el.querySelector("#ciBtn");
            b.disabled = true;
            b.textContent = "Checking in...";
            if (!navigator.geolocation) { save("", ""); return; }
            navigator.geolocation.getCurrentPosition(
                function (p) { save(p.coords.latitude.toFixed(6), p.coords.longitude.toFixed(6)); },
                function () { save("", ""); },
                { timeout: 8000 });
        }
        draw();
    }

    /* ---------- charts (no external libraries) ---------- */
    function columns(labels, values, fmt) {
        fmt = fmt || function (v) { return v; };
        var max = Math.max.apply(null, values.concat([1])), w = 420, step = (w - 20) / labels.length;
        var bw = Math.min(44, step - 12);
        var s = '<svg viewBox="0 0 ' + w + ' 190" class="chart-svg" role="img">';
        values.forEach(function (v, i) {
            var bh = Math.round(v / max * 120), x = 10 + i * step + (step - bw) / 2, y = 150 - bh;
            s += '<rect x="' + x + '" y="' + y + '" width="' + bw + '" height="' + bh + '" rx="3" fill="#f59e0b"></rect>' +
                '<text x="' + (x + bw / 2) + '" y="' + (y - 5) + '" text-anchor="middle" font-size="11" fill="#333">' + esc(fmt(v)) + "</text>" +
                '<text x="' + (x + bw / 2) + '" y="168" text-anchor="middle" font-size="11" fill="#666">' + esc(labels[i]) + "</text>";
        });
        return s + '<line x1="10" y1="150" x2="' + (w - 10) + '" y2="150" stroke="#ddd"></line></svg>';
    }
    function hbars(pairs, fmt) {
        if (!pairs.length) return '<p class="muted">No data yet.</p>';
        var max = Math.max.apply(null, pairs.map(function (p) { return p[1]; }).concat([1]));
        return pairs.map(function (p) {
            return '<div class="hbar"><span class="hbar-label">' + esc(p[0]) + '</span><span class="hbar-track"><span class="hbar-fill" style="width:' +
                Math.round(p[1] / max * 100) + '%"></span></span><span class="hbar-val">' + esc(fmt ? fmt(p[1]) : p[1]) + "</span></div>";
        }).join("");
    }

    /* ---------- page chrome ---------- */
    var ROLE_NAV = {
        customer: [["customer.html", "My Schedule"], ["schedule.html", "Book a Service"], ["products.html", "Products"], ["quote.html", "Get a Quote"]],
        technician: [["technician.html", "Check In"], ["tech-jobs.html", "My Jobs"], ["tech-announcements.html", "Announcements"], ["tech-report.html", "Report a Problem"]],
        admin: [["admin.html", "Admin Panel"], ["timein.html", "Check In"]]
    };
    var ADMIN_TABS = [
        ["admin.html", "Users"], ["admin-calendar.html", "Bookings"], ["admin-quotes.html", "Quotes"], ["admin-inbox.html", "Inbox"],
        ["admin-attendance.html", "Attendance"], ["admin-reports.html", "Reports"], ["admin-notify.html", "Notifications"], ["admin-settings.html", "Settings"]
    ];
    function links(arr) {
        return arr.map(function (a) { return '<a href="' + a[0] + '"' + (a[0] === page() ? ' class="current"' : "") + ">" + a[1] + "</a>"; }).join("");
    }
    function header(user, role) {
        var n = unread(user.uname);
        return '<header class="navbar"><div class="logo" onclick="location.href=\'' + homeFor(user) + '\'">Sun Son <span>Solar</span></div><nav class="nav-links">' +
            links(ROLE_NAV[role] || []) +
            '<a href="notifications.html" class="bell" title="Notifications" aria-label="Notifications, ' + n + ' unread">&#128276;' + (n ? '<b class="count">' + n + "</b>" : "") + "</a>" +
            '<div class="dropdown"><button type="button" class="dropdown-btn">' + esc(user.fname) + '</button><div class="dropdown-menu">' +
            links(ROLE_MENU[role] || []) + '<a href="SunSonSolarLanding.html">Home Page</a><a href="#" id="pLogout">Log Out</a></div></div></nav></header>' +
            (role === "admin" ? '<nav class="subnav" aria-label="Admin sections">' + links(ADMIN_TABS) + "</nav>" : "");
    }
    function publicHeader() {
        return '<header class="navbar"><div class="logo" onclick="location.href=\'SunSonSolarLanding.html\'">Sun Son <span>Solar</span></div><nav class="nav-links">' +
            '<a href="SunSonSolarLanding.html">Home</a><a href="products.html">Products</a><a href="quote.html">Get a Quote</a><a href="login.html">Log In</a></nav></header>';
    }
    function show() {
        document.body.style.display = "";
        var f = sessionStorage.getItem("ssFlash");
        if (f) { sessionStorage.removeItem("ssFlash"); toast(f); }
    }

    // Call first on every portal page. Returns the signed-in user, or null (page is being redirected).
    // opts.roles  - roles allowed on this page
    // opts.public - page is also usable while signed out
    function init(opts) {
        opts = opts || {};
        document.body.classList.add("portal");
        var user = getCurrentUser();
        if (!user) {
            if (opts.public) { document.body.insertAdjacentHTML("afterbegin", publicHeader()); show(); return null; }
            requireLogin();
            return null;
        }
        var fresh = findAccount(user.uname);
        if (!fresh || fresh.active === false) { logoutAccount(); window.location.replace("login.html"); return null; }
        user = fresh;
        sessionStorage.setItem("ssCurrentUser", JSON.stringify(user));
        var role = roleOf(user);
        if (opts.roles && opts.roles.indexOf(role) === -1) {
            sessionStorage.setItem("ssFlash", "That page isn't available for your account.");
            window.location.replace(homeFor(user));
            return null;
        }
        document.body.insertAdjacentHTML("afterbegin", header(user, role));
        document.getElementById("pLogout").onclick = function (e) {
            e.preventDefault();
            logoutAccount();
            window.location.href = "SunSonSolarLanding.html";
        };
        show();
        return user;
    }

    /* ---------- sample data (so every page has something to show) ---------- */
    var DATA_KEYS = ["ssBookings", "ssPayments", "ssNotifs", "ssNotifLog", "ssAnnouncements", "ssReports", "ssTickets", "ssQuotes", "ssTimeLogs"];

    function clearData() {
        DATA_KEYS.forEach(function (k) { localStorage.removeItem(k); });
        set("ssSeeded", true);
    }

    function seedDemo(force) {
        if (!force && get("ssSeeded", false)) return;
        if (force) clearData();
        set("ssSeeded", true);
        var now = new Date(), sv = {}, bk = [], pay = [], n = 0;
        services().forEach(function (s) { sv[s.id] = s; });
        function mk(d, sid, status, tech) {
            var s = sv[sid];
            if (!s) return;
            n++;
            var r = {
                id: "demo-b" + n, customer: "customer1", customerName: "Maria Cruz", service: s.name, category: s.cat, date: iso(d), time: "09:00",
                address: "123 Sampaguita St., Pasig City", notes: "", status: status, tech: tech || "", price: s.price,
                created: new Date(d.getTime() - 5 * 864e5).toISOString()
            };
            bk.push(r);
            if (status === "completed") {
                pay.push({ id: "demo-p" + n, receipt: "SSS-DEMO-" + (1000 + n), bookingId: r.id, customer: r.customer, service: r.service, amount: r.price, date: r.date, method: "Cash" });
            }
        }
        function ago(m, day) { return new Date(now.getFullYear(), now.getMonth() - m, day); }
        function plus(k) { return new Date(now.getFullYear(), now.getMonth(), now.getDate() + k); }
        [[5, 6, "serv-1", "technician1"], [5, 19, "inst-1", "technician2"], [4, 8, "maint-1", "technician1"], [4, 22, "mon-1", "technician1"],
         [3, 11, "inst-2", "technician2"], [3, 25, "rep-1", "technician2"], [2, 9, "serv-5", "technician1"], [2, 20, "maint-2", "technician1"],
         [1, 7, "des-1", "technician2"], [1, 18, "inst-4", "technician2"], [0, 1, "maint-3", "technician1"]].forEach(function (r) { mk(ago(r[0], r[1]), r[2], "completed", r[3]); });
        mk(plus(0), "maint-1", "approved", "technician1");
        mk(plus(2), "rep-1", "approved", "technician1");
        mk(plus(1), "serv-1", "pending", "");
        mk(plus(4), "inst-1", "pending", "");
        mk(plus(-3), "mon-2", "cancelled", "");
        mk(plus(-6), "des-3", "declined", "");
        set("ssBookings", bk);
        set("ssPayments", pay);

        var logs = [];
        for (var i = 1; i <= 5; i++) {
            ["technician1", "technician2"].forEach(function (u, j) {
                var d = new Date(now.getFullYear(), now.getMonth(), now.getDate() - i, 8, 5 + j * 12);
                if (i === 3 && j === 1) return;
                logs.push({ uname: u, name: nameOf(u), time: d.toLocaleString(), ts: d.toISOString(), date: iso(d), lat: "14.5764", lng: "121.0851" });
            });
        }
        set("ssTimeLogs", (force ? [] : getTimeLogs()).concat(logs));

        set("ssAnnouncements", [{ id: "demo-a1", title: "Safety reminder", body: "Wear your harness on every roof job and check ladders before climbing. Report any damaged equipment right away.", ts: new Date().toISOString(), by: "admin" }]);
        set("ssNotifs", [
            { id: "demo-n1", to: "customer1", title: "Service approved", body: "Your Annual System Health Check is confirmed. A technician has been assigned.", ts: new Date().toISOString(), read: false },
            { id: "demo-n2", to: "technician1", title: "New job assigned", body: "You have been assigned to Annual System Health Check & Panel Washing.", ts: new Date().toISOString(), read: false }
        ]);
        set("ssQuotes", [{ id: "demo-q1", name: "Jose Ramirez", email: "jose@example.com", phone: "09171112222", service: "Residential Solar Installation", items: [], notes: "3-storey house, roof faces south.", ts: new Date().toISOString(), status: "new", customer: "" }]);
        set("ssTickets", [{ id: "demo-t1", customer: "customer1", name: "Maria Cruz", subject: "Question about my inverter app", message: "The app shows 0 kW in the morning. Is that normal?", ts: new Date().toISOString(), status: "open", replies: [] }]);
        set("ssReports", [{ id: "demo-r1", tech: "technician1", techName: "Tomas Reyes", jobId: "", jobLabel: "Not about a specific job", type: "Equipment", details: "One of the ladders has a loose rung. Needs replacing.", ts: new Date().toISOString(), status: "open", note: "" }]);
    }

    seedDemo(false);

    return {
        get: get, set: set, uid: uid, esc: esc, iso: iso, today: today, addDays: addDays, fmtDate: fmtDate, fmtTime: fmtTime, fmtStamp: fmtStamp,
        peso: peso, fullName: fullName, nameOf: nameOf, chip: chip, toast: toast, exportCsv: exportCsv, NO_IMG: NO_IMG,
        passwordProblem: passwordProblem, accountProblem: accountProblem, settings: settings,
        notify: notify, notifyIf: notifyIf, notifyRole: notifyRole, unread: unread,
        bookings: bookings, saveBookings: saveBookings, updateBooking: updateBooking, byWhen: byWhen, services: services, technicians: technicians,
        dayFull: dayFull, slotOptions: slotOptions, recordPayment: recordPayment, reschedule: reschedule,
        logDate: logDate, checkin: checkin, columns: columns, hbars: hbars, init: init, seedDemo: seedDemo, clearData: clearData
    };
})();
