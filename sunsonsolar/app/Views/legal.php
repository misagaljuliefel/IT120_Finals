<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Privacy & Terms</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
    <link rel="stylesheet"    href="<?= base_url('assets/SunSonSolarPortal.css') ?>" />
</head>
<body style="display:none">
    <main class="legal">
        <p><a href="<?= base_url('SunSonSolarLanding') ?>">&larr; Back to home page</a></p>
        <p class="muted">Sample wording for the website. Have it reviewed by a lawyer before launch.</p>
        <h2 id="privacy">Privacy Policy</h2>
        <h3>What we collect</h3>
        <p>When you register, book a service or request a quote we collect your name, contact details, address and the services you ask for. Employees also give a check-in time and, if allowed, their location when they check in.</p>
        <h3>How we use it</h3>
        <p>We use your information to schedule and complete services, send notifications about your bookings, issue receipts and answer support requests. We do not sell your personal information.</p>
        <h3>Your choices</h3>
        <p>You can update your details and notification preferences in Account Settings, or contact us to correct or delete your information.</p>
        <h2 id="terms">Terms of Service</h2>
        <h3>Bookings</h3>
        <p>A schedule is confirmed only after Sun Son Solar approves it. We may decline or move a booking and will tell you why. Please cancel or reschedule before the technician arrives.</p>
        <h3>Prices</h3>
        <p>Prices shown are starting prices. The final amount is confirmed after a site evaluation and approval.</p>
        <h3>Warranty</h3>
        <p>Warranties follow the terms given with your installation paperwork.</p>
        <h3>Contact</h3>
        <p id="contact"></p>
    </main>

    <script src="<?= base_url('assets/SunSonSolarData.js') ?>"></script>
    <script src="<?= base_url('assets/SunSonSolarPortal.js') ?>"></script>
    <script>
        P.init({ public: true });
        const s = P.settings();
        document.getElementById("contact").textContent = s.company + " | " + s.address + " | " + s.phone + " | " + s.email;
    </script>
</body>
</html>
