
<?php
$users = $users ?? [];
$logs  = $logs ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Panel | Sun Son Solar</title>

    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>">
</head>
<body>

    <header class="navbar">
        <div class="logo">Sun Son <span>Solar</span></div>

        <nav class="nav-links">
            <a href="<?= site_url('dashboard') ?>">Dashboard</a>

            <div class="dropdown">
                <button class="dropdown-btn">My Account</button>

                <div class="dropdown-menu">
                    <a href="<?= site_url('dashboard') ?>">Dashboard</a>
                    <a href="<?= site_url('timein') ?>">Time In</a>
                    <a href="<?= site_url('account') ?>">Account Settings</a>
                    <a href="<?= site_url('admin') ?>">Admin Panel</a>
                    <a href="<?= site_url('logout') ?>">Log Out</a>
                </div>
                </div>
                </nav>
                </header>

            <main class="admin-wrap">

        <div class="admin-heading">
            <h1>Admin Panel</h1>
            <p>Manage users and monitor employee attendance.</p>
        </div>

        <section class="admin-box">
            <h2>Registered Users</h2>

            <div class="table-container">
                <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Email</th>
                </tr>
                </thead>

            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                <td colspan="5">No registered users found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= esc(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></td>
                    <td><?= esc($user['username'] ?? '') ?></td>
                    <td><?= esc($user['role'] ?? '') ?></td>
                    <td><?= esc($user['department'] ?? '-') ?></td>
                    <td><?= esc($user['email'] ?? '-') ?></td>
                </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                </table>
            </div>
           </section>

         <section class="admin-box">
            <h2>Employee Time-In Log</h2>

            <div class="table-container">
            <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Time</th>
                    <th>Coordinates</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="3">No time-in records found.</td>
                </tr>
                    <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= esc($log['first_name'] . ' ' . $log['last_name']) ?></td>
                    <td><?= esc($log['time_in']) ?></td>
                <td>
                     <?= esc($log['latitude'] ?? '-') ?>,
                     <?= esc($log['longitude'] ?? '-') ?>
                </td>
                </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                </table>
        </div>
        </section>

    </main>

</body>
</html>
