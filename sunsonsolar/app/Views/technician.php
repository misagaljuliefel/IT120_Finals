<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Sun Son Solar - Technician Home<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="page" style="max-width:680px">
        <h1 id="hello">Hello</h1>
        <div class="admin-box"><div id="checkin"></div></div>
        <div class="big-tiles">
            <a class="big-tile" href="<?= base_url('tech-jobs') ?>">My Jobs<small id="jobsInfo"></small></a>
            <a class="big-tile" href="<?= base_url('tech-announcements') ?>">Announcements<small id="annInfo"></small></a>
            <a class="big-tile" href="<?= base_url('tech-report') ?>">Report a Problem<small>Tell the office about an issue</small></a>
        </div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const user = P.init({ roles: ["technician"] });
        if (user) {
            document.getElementById("hello").textContent = "Hello, " + user.fname;
            P.checkin(document.getElementById("checkin"), user);
            const mine = P.bookings().filter(function (b) { return b.tech === user.uname && b.status === "approved"; });
            const todayCount = mine.filter(function (b) { return b.date === P.today(); }).length;
            document.getElementById("jobsInfo").textContent = todayCount ? todayCount + " job" + (todayCount === 1 ? "" : "s") + " today" : mine.length + " upcoming";
            const ann = P.get("ssAnnouncements", []);
            document.getElementById("annInfo").textContent = ann.length ? "Latest: " + ann[0].title : "Nothing new";
        }
    </script>
<?= $this->endSection() ?>