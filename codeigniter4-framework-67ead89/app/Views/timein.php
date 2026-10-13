
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance | Sun Son Solar</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>">
</head>
<body>
    <header class="navbar">
        <div class="logo">Sun Son <span>Solar</span></div>

        <nav class="nav-links">
            <a href="<?= site_url('dashboard') ?>">Dashboard</a>
            <a href="<?= site_url('logout') ?>">Log Out</a>
        </nav>
    </header>

    <main class="admin-wrap">
        <section class="admin-box">
            <h1>Employee Attendance</h1>
            <p>Record your attendance for today.</p>

            <?php if (session()->getFlashdata('success')): ?>
                <p role="status">
                    <?= esc(session()->getFlashdata('success')) ?>
                </p>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <p role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                </p>
            <?php endif; ?>

            <form action="<?= site_url('timein/save') ?>" method="post">
                <?= csrf_field() ?>

                <button type="submit" name="action" value="time_in">
                    Time In
                </button>

                <button type="submit" name="action" value="time_out">
                    Time Out
                </button>
         </form>
        </section>
        </main>
</body>
</html>
