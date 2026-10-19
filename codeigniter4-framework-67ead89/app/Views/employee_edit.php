
<?php
$employee = $employee ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - Sun Son Solar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 30px auto;
            padding: 20px;
            color: #222;
        }

        label {
            display: block;
            margin-top: 14px;
            font-weight: bold;
        }

        input, select {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        button, a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
        }

        .error {
            color: #b00020;
        }
    </style>
</head>
<body>

    <h1>Edit Employee</h1>
    <p>Update the employee's information below.</p>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul class="error">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= site_url('admin/employees/update/' . ($employee['id'] ?? '')) ?>" method="post">
        <?= csrf_field() ?>

        <label for="first_name">First Name</label>
        <input id="first_name" name="first_name" value="<?= esc($employee['first_name'] ?? '') ?>" required>

        <label for="middle_name">Middle Name</label>
        <input id="middle_name" name="middle_name" value="<?= esc($employee['middle_name'] ?? '') ?>">

        <label for="last_name">Last Name</label>
        <input id="last_name" name="last_name" value="<?= esc($employee['last_name'] ?? '') ?>" required>

        <label for="birth_date">Birth Date</label>
        <input type="date" id="birth_date" name="birth_date" value="<?= esc($employee['birth_date'] ?? '') ?>" required>

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Select gender</option>
            <?php foreach (['Male', 'Female', 'Other'] as $gender): ?>
                <option value="<?= esc($gender) ?>" <?= ($employee['gender'] ?? '') === $gender ? 'selected' : '' ?>>
                    <?= esc($gender) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= esc($employee['email'] ?? '') ?>" required>

        <label for="phone">Phone</label>
        <input id="phone" name="phone" value="<?= esc($employee['phone'] ?? '') ?>" required>

        <label for="address">Address</label>
        <input id="address" name="address" value="<?= esc($employee['address'] ?? '') ?>">

        <label for="username">Username</label>
        <input id="username" name="username" value="<?= esc($employee['username'] ?? '') ?>" required>

        <label for="department">Department</label>
        <select id="department" name="department" required>
            <option value="">Select department</option>
            <?php foreach ([
                'Administration',
                'IT',
                'Dispatch',
                'Accounting',
                'HR',
                'Marketing',
                'Sales',
                'Customer Service'
            ] as $department): ?>
                <option value="<?= esc($department) ?>" <?= ($employee['department'] ?? '') === $department ? 'selected' : '' ?>>
                    <?= esc($department) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="status">Account Status</label>
        <select id="status" name="status" required>
            <option value="active" <?= ($employee['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= ($employee['status'] ?? 'active') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>

        <button type="submit">Save Changes</button>
        <a href="<?= site_url('admin') ?>">Cancel</a>
    </form>

</body>
</html>
