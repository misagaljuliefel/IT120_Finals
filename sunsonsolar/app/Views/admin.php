<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$users = $users ?? [];
$logs  = $logs ?? [];
?>

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

<?= $this->endSection() ?>