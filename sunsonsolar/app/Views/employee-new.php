<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Add Employee</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
    <link rel="stylesheet"    href="<?= base_url('assets/SunSonSolarPortal.css') ?>" />
</head>
<body style="display:none">
    <main class="page" style="max-width:720px">
        <h1>Add an Employee</h1>
        <p class="lead">Only HR and administrators can create employee accounts. The department decides which pages the person can open.</p>
        <div class="admin-box">
            <form id="empForm">
                <div class="row">
                    <div><label for="fname">First name:</label><input type="text" id="fname" required style="width:100%" /></div>
                    <div><label for="lname">Last name:</label><input type="text" id="lname" required style="width:100%" /></div>
                </div>
                <label for="mname">Middle name (optional):</label>
                <input type="text" id="mname" />
                <div class="row">
                    <div><label for="bdate">Birth date:</label><input type="date" id="bdate" required style="width:100%" /></div>
                    <div><label for="gender">Gender:</label><select id="gender" required style="width:100%"><option value="">Select</option><option>Male</option><option>Female</option></select></div>
                </div>
                <div class="row">
                    <div><label for="email">Work email:</label><input type="email" id="email" required style="width:100%" /></div>
                    <div><label for="phone">Phone:</label><input type="tel" id="phone" placeholder="09123456789" required style="width:100%" /></div>
                </div>
                <label for="address">Address:</label>
                <input type="text" id="address" required />
                <label for="department">Department:</label>
                <select id="department" required></select>
                <p class="muted" id="roleHint" style="margin:-8px 0 12px"></p>
                <div class="row">
                    <div><label for="uname">Username:</label><input type="text" id="uname" required style="width:100%" /></div>
                    <div><label for="password">Temporary password:</label><input type="text" id="password" required style="width:100%" /></div>
                </div>
                <button type="submit">Create employee account</button>
            </form>
        </div>
    </main>

    <script src="<?= base_url('assets/SunSonSolarData.js') ?>"></script>
    <script src="<?= base_url('assets/SunSonSolarPortal.js') ?>"></script>
    <script>
        const user = P.init({ roles: ["admin"] });
        if (user) {
            const dep = document.getElementById("department");
            dep.innerHTML = '<option value="">Select a department</option>' + DEPARTMENTS.map(function (d) { return "<option>" + P.esc(d) + "</option>"; }).join("");
            dep.addEventListener("change", function () {
                document.getElementById("roleHint").textContent = dep.value ? (roleForDepartment(dep.value) === "technician" ? "Gets the technician pages: check-in, jobs, announcements, problem reports." : "Gets the admin pages: users, bookings, reports and settings.") : "";
            });
            document.getElementById("password").value = "Sun" + Math.floor(1000 + Math.random() * 9000) + "!";

            document.getElementById("empForm").addEventListener("submit", function (e) {
                e.preventDefault();
                const v = function (id) { return document.getElementById(id).value.trim(); };
                const a = { fname: v("fname"), lname: v("lname"), mname: v("mname"), bdate: v("bdate"), gender: v("gender"), email: v("email"), phone: v("phone"), address: v("address"), uname: v("uname"), password: v("password"), department: dep.value };
                const problem = P.accountProblem(a, { checkUname: true, password: true }) || (a.department ? "" : "Please choose a department.");
                if (problem) { P.toast(problem); return; }
                if (!registerAccount(a)) return;
                P.toast(P.fullName(a) + " was added as " + roleForDepartment(a.department) + ". Share the temporary password with them.");
                this.reset();
                document.getElementById("roleHint").textContent = "";
                document.getElementById("password").value = "Sun" + Math.floor(1000 + Math.random() * 9000) + "!";
            });
        }
    </script>
</body>
</html>
