<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Account Settings<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container wide">
<h1>Sun Son Solar</h1>
<h2>Account Settings</h2>
<form id="acctForm">
<div class="row">
<div><label for="fname">First name:</label><input type="text" id="fname" required style="width:100%" /></div>
<div><label for="lname">Last name:</label><input type="text" id="lname" required style="width:100%" /></div>
</div>
<label for="mname">Middle name:</label>
<input type="text" id="mname" />
<label for="bdate">Birth date:</label>
<input type="date" id="bdate" required />
<label>Gender:</label>
<div class="gender-options">
<label><input type="radio" name="gender" value="Male" id="genderMale" /> Male</label>
<label><input type="radio" name="gender" value="Female" id="genderFemale" /> Female</label>
</div>
<label for="email">Email:</label>
<input type="email" id="email" required />
<label for="phone">Phone number:</label>
<input type="tel" id="phone" required />
<label for="address">Address:</label>
<input type="text" id="address" required />
<label for="uname">Username:</label>
<input type="text" id="uname" disabled />
<p class="muted" id="deptLine" style="margin:-8px 0 12px"></p>
<h3 style="margin:10px 0">Preferences</h3>
<label style="font-weight:normal"><input type="checkbox" id="prefEmail" style="margin:0 8px 0 0" /> Email me about my bookings and receipts</label>
<label style="font-weight:normal;margin-top:8px"><input type="checkbox" id="prefSms" style="margin:0 8px 0 0" /> Text me service reminders</label>
<label for="prefContact" style="margin-top:12px">Best way to reach me:</label>
<select id="prefContact"><option value="phone">Phone call</option><option value="sms">Text message</option><option value="email">Email</option></select>
<h3 style="margin:10px 0">Change password</h3>
<label for="curPass">Current password:</label>
<input type="password" id="curPass" placeholder="Only needed when changing your password" autocomplete="current-password" />
<label for="password">New password:</label>
<input type="password" id="password" placeholder="Leave blank to keep your current password" autocomplete="new-password" />
<label for="cpassword">Confirm new password:</label>
<input type="password" id="cpassword" autocomplete="new-password" />
<button type="submit">Save changes</button>
</form>
<p class="form-note"><a href="#" id="back">&larr; Back</a></p>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const user = P.init({});
if (user) {
const $ = function (id) { return document.getElementById(id); };
["fname", "lname", "mname", "bdate", "email", "phone", "address", "uname"].forEach(function (k) { $(k).value = user[k] || ""; });
if (user.gender === "Male") $("genderMale").checked = true;
if (user.gender === "Female") $("genderFemale").checked = true;
if (user.department) $("deptLine").textContent = "Department: " + user.department + " (changed by HR or an administrator)";
const prefs = user.prefs || { email: true, sms: false, contact: "phone" };
$("prefEmail").checked = !!prefs.email;
$("prefSms").checked = !!prefs.sms;
$("prefContact").value = prefs.contact || "phone";
$("back").href = homeFor(user);
$("acctForm").addEventListener("submit", function (e) {
e.preventDefault();
const g = document.querySelector('input[name="gender"]:checked');
const d = {
fname: $("fname").value.trim(), lname: $("lname").value.trim(), mname: $("mname").value.trim(), bdate: $("bdate").value,
gender: g ? g.value : "", email: $("email").value.trim(), phone: $("phone").value.trim(), address: $("address").value.trim()
};
let problem = P.accountProblem(d);
const taken = getAccounts().some(function (a) { return a.uname !== user.uname && (a.email || "").toLowerCase() === d.email.toLowerCase(); });
if (!problem && taken) problem = "That email address is already used by another account.";
const np = $("password").value;
if (!problem && np) {
if ($("curPass").value !== user.password) problem = "Your current password is not correct.";
else if (P.passwordProblem(np)) problem = P.passwordProblem(np);
else if (np !== $("cpassword").value) problem = "New passwords do not match.";
}
if (problem) { P.toast(problem); return; }
d.prefs = { email: $("prefEmail").checked, sms: $("prefSms").checked, contact: $("prefContact").value };
if (np) d.password = np;
updateAccount(user.uname, d);
sessionStorage.setItem("ssFlash", "Your account has been updated.");
window.location.reload();
});
}
</script>
<?= $this->endSection() ?>
