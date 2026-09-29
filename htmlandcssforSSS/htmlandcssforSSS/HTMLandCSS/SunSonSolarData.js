// Sun Son Solar - front-end data & session handling.
// Until the PHP backend is wired in, accounts/logs live in localStorage
// and the logged-in session lives in sessionStorage.

function seedAccounts() {
    if (localStorage.getItem("ssAccounts")) {
        return;
    }
    var accounts = [
        {
            fname: "Katherine", lname: "Sinagaraw", mname: "", bdate: "", gender: "",
            email: "kat@sunsonsolar.com", phone: "", address: "Pasig City",
            uname: "KittyKat16", password: "K@tSunShine16",
            role: "admin", department: "Founder"
        },
        {
            fname: "Sol", lname: "Solis", mname: "", bdate: "", gender: "",
            email: "sol@sunsonsolar.com", phone: "", address: "Pasig City",
            uname: "admin", password: "admin123",
            role: "admin", department: "IT"
        }
    ];
    localStorage.setItem("ssAccounts", JSON.stringify(accounts));
}
seedAccounts();

function getAccounts() {
    return JSON.parse(localStorage.getItem("ssAccounts") || "[]");
}

function saveAccounts(accounts) {
    localStorage.setItem("ssAccounts", JSON.stringify(accounts));
}

function findAccount(uname) {
    var accounts = getAccounts();
    for (var i = 0; i < accounts.length; i++) {
        if (accounts[i].uname.toLowerCase() === uname.toLowerCase()) {
            return accounts[i];
        }
    }
    return null;
}

// Registers a new account. On the employee form, only the IT department
// gets the "admin" role (Admin Panel access); other departments get
// "employee". The customer form (no department field) gets "customer".
function registerAccount(account) {
    if (findAccount(account.uname)) {
        alert("That username is already taken. Please choose another.");
        return false;
    }

    if (account.department) {
        account.role = (account.department === "IT") ? "admin" : "employee";
    } else {
        account.role = "customer";
    }

    var accounts = getAccounts();
    accounts.push(account);
    saveAccounts(accounts);
    return true;
}

function updateAccount(oldUname, updates) {
    var accounts = getAccounts();
    for (var i = 0; i < accounts.length; i++) {
        if (accounts[i].uname.toLowerCase() === oldUname.toLowerCase()) {
            for (var key in updates) {
                accounts[i][key] = updates[key];
            }
            saveAccounts(accounts);
            sessionStorage.setItem("ssCurrentUser", JSON.stringify(accounts[i]));
            return true;
        }
    }
    return false;
}

function loginAccount(uname, password) {
    var account = findAccount(uname);
    if (!account || account.password !== password) {
        return false;
    }
    sessionStorage.setItem("ssCurrentUser", JSON.stringify(account));
    return true;
}

function getCurrentUser() {
    var raw = sessionStorage.getItem("ssCurrentUser");
    return raw ? JSON.parse(raw) : null;
}

function logoutAccount() {
    sessionStorage.removeItem("ssCurrentUser");
    sessionStorage.removeItem("ssQuoteList");
}

// Sends the visitor to the login page if nobody is logged in.
function requireLogin() {
    var user = getCurrentUser();
    if (!user) {
        window.location.href = "login.html";
    }
    return user;
}

// Same as requireLogin, but also checks the user's role.
function requireRole(allowedRoles) {
    var user = requireLogin();
    if (user && allowedRoles.indexOf(user.role) === -1) {
        alert("You don't have access to that page.");
        window.location.href = "dashboard.html";
        return null;
    }
    return user;
}

// ---- Time-in log ----

function addTimeLog(entry) {
    var logs = JSON.parse(localStorage.getItem("ssTimeLogs") || "[]");
    logs.unshift(entry);
    localStorage.setItem("ssTimeLogs", JSON.stringify(logs));
}

function getTimeLogs() {
    return JSON.parse(localStorage.getItem("ssTimeLogs") || "[]");
}

// ---- Quote list (session only) ----

function getQuoteList() {
    return JSON.parse(sessionStorage.getItem("ssQuoteList") || "[]");
}

function addToQuoteList(item) {
    var list = getQuoteList();
    for (var i = 0; i < list.length; i++) {
        if (list[i].id === item.id) {
            return list;
        }
    }
    list.push(item);
    sessionStorage.setItem("ssQuoteList", JSON.stringify(list));
    return list;
}

function removeFromQuoteList(id) {
    var list = getQuoteList().filter(function (item) {
        return item.id !== id;
    });
    sessionStorage.setItem("ssQuoteList", JSON.stringify(list));
    return list;
}

function clearQuoteList() {
    sessionStorage.removeItem("ssQuoteList");
}

// ---- Product/service catalog ----

var CATALOG = [
    { id: "panel", type: "product", cat: "Panels", name: "Solar Panels", desc: "High-efficiency monocrystalline solar panels built for long durability." },
    { id: "inverter", type: "product", cat: "Inverters", name: "Inverters", desc: "Advanced power inverters for reliable off-grid and hybrid energy conversion." },
    { id: "battery", type: "product", cat: "Batteries", name: "Batteries", desc: "Long-lasting energy storage units to keep your setup powered 24/7." },
    { id: "racking", type: "product", cat: "Racking & Mounting", name: "Racking & Mounting", desc: "Heavy-duty structural mounting systems for rooftops and ground setups." },
    { id: "wires", type: "product", cat: "Wires", name: "Solar Wires & Cabling", desc: "Weatherproof, high-conductivity wiring and solar PV connectors." },
    { id: "consultation", type: "service", cat: "Consultation", name: "Consultation", desc: "Professional site evaluation and solar energy cost analysis." },
    { id: "designing", type: "service", cat: "Designing", name: "Designing", desc: "Custom engineering plans optimized for your power usage." },
    { id: "permitting", type: "service", cat: "Permitting", name: "Permitting", desc: "Hassle-free grid connection and documentation handling." },
    { id: "installation", type: "service", cat: "Installation", name: "Installations", desc: "Certified turn-key solar panel array setup and wiring." },
    { id: "maintenance", type: "service", cat: "Maintenance", name: "Maintenance", desc: "Regular system health checks and panel cleaning services." },
    { id: "repair", type: "service", cat: "Repair", name: "Repair", desc: "Fast component diagnosis, battery swaps, and inverter repairs." },
    { id: "monitoring", type: "service", cat: "Monitoring", name: "Monitoring", desc: "24/7 remote performance tracking and alerts for peak output." }
];

// ---- Wires up the existing static "My Account" dropdown ----
// Does not rebuild any markup - just adjusts what's already there:
// shows the user's name on the button, hides Admin Panel for non-admins,
// and clears the session when Log Out is clicked.
function wireUserMenu() {
    var menu = document.getElementById("userMenu");
    if (!menu) {
        return;
    }
    var user = getCurrentUser();
    if (!user) {
        return;
    }

    var btn = menu.querySelector(".dropdown-btn");
    if (btn) {
        btn.textContent = user.fname;
    }

    var adminLink = menu.querySelector('a[href="admin.html"]');
    if (adminLink && user.role !== "admin") {
        adminLink.style.display = "none";
    }

    var logoutLink = menu.querySelector('a[href="SunSonSolarLanding.html"]');
    if (logoutLink) {
        logoutLink.addEventListener("click", function () {
            logoutAccount();
        });
    }
}
