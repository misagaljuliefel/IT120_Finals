<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Sun Son Solar - Forgot Password<?= $this->endSection() ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url('assets/SunSonSolarPortal.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container">
        <h1>Sun Son Solar</h1>
        <h2>Reset Your Password</h2>

        <form id="findForm">
            <p class="muted" style="margin-bottom:12px">Enter your username and the email address on your account.</p>
            <label for="fUname">Username:</label>
            <input type="text" id="fUname" required />
            <label for="fEmail">Email:</label>
            <input type="email" id="fEmail" required />
            <button type="submit">Continue</button>
        </form>

        <form id="newForm" style="display:none">
            <p class="muted" style="margin-bottom:12px">Account found. Choose a new password.</p>
            <label for="nPass">New password:</label>
            <input type="password" id="nPass" required autocomplete="new-password" />
            <label for="nPass2">Confirm new password:</label>
            <input type="password" id="nPass2" required autocomplete="new-password" />
            <button type="submit">Save new password</button>
        </form>

        <p class="form-note"><a href="<?= base_url('login') ?>">&larr; Back to log in</a></p>
        <p class="form-note"><small>Demo note: once the site has a server, this step should email a reset link instead.</small></p>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/SunSonSolarData.js') ?>"></script>
    <script src="<?= base_url('assets/SunSonSolarPortal.js') ?>"></script>
    <script>
        let found = null;
        document.getElementById("findForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const a = findAccount(document.getElementById("fUname").value.trim());
            const email = document.getElementById("fEmail").value.trim().toLowerCase();
            if (!a || (a.email || "").toLowerCase() !== email) { alert("We couldn't find an account with that username and email."); return; }
            if (a.active === false) { alert("This account is deactivated. Please contact an administrator."); return; }
            found = a;
            document.getElementById("findForm").style.display = "none";
            document.getElementById("newForm").style.display = "flex";
        });
        document.getElementById("newForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const p = document.getElementById("nPass").value;
            const problem = P.passwordProblem(p);
            if (problem) { alert(problem); return; }
            if (p !== document.getElementById("nPass2").value) { alert("Passwords do not match."); return; }
            const wasLoggedIn = getCurrentUser();
            updateAccount(found.uname, { password: p });
            if (!wasLoggedIn) sessionStorage.removeItem("ssCurrentUser");
            alert("Password updated. You can now log in.");
            window.location.href = "<?= base_url('login') ?>";
        });
    </script>
<?= $this->endSection() ?>