<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
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
<?= $this->endSection() ?>