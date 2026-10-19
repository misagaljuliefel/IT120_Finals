
<?php
$employee = $employee ?? [];
$departments = [
    'Administration',
    'IT',
    'Dispatch',
    'Accounting',
    'HR',
    'Marketing',
    'Sales',
    'Customer Service',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Edit Employee</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>">
</head>
<body>

    <div class="container">
        <h1>Sun Son Solar</h1>
        <h2>Edit Employee Account</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <p class="error-message">
                <?= esc(session()->getFlashdata('error')) ?>
            </p>
        <?php endif; ?>

        <form action="<?= site_url('admin/employees/update/' . $employee['id']) ?>" method="post">
            <?= csrf_field() ?>

            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname"
                   value="<?= esc(old('fname', $employee['first_name'] ?? '')) ?>" required />

            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname"
                   value="<?= esc(old('lname', $employee['last_name'] ?? '')) ?>" required />

            <label for="mname">Middle Name:</label>
            <input type="text" name="mname" id="mname"
                   value="<?= esc(old('mname', $employee['middle_name'] ?? '')) ?>" />

            <label for="bdate">Birth Date:</label>
            <input type="date" name="bdate" id="bdate"
                   value="<?= esc(old('bdate', $employee['birth_date'] ?? '')) ?>" required />

            <label>Gender:</label>
            <div class="gender-options">
                <label>
                    <input type="radio" name="gender" value="Male"
                        <?= old('gender', $employee['gender'] ?? '') === 'Male' ? 'checked' : '' ?> required />
                    Male
                </label>
                <label>
                    <input type="radio" name="gender" value="Female"
                        <?= old('gender', $employee['gender'] ?? '') === 'Female' ? 'checked' : '' ?> />
                    Female
                </label>
            </div>

            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" id="phone"
                   value="<?= esc(old('phone', $employee['phone'] ?? '')) ?>" required />

            <label for="address">Address:</label>
            <input type="text" name="address" id="address"
                   value="<?= esc(old('address', $employee['address'] ?? '')) ?>" required />

            <label for="email">Email:</label>
            <input type="email" name="email" id="email"
                   value="<?= esc(old('email', $employee['email'] ?? '')) ?>" required />

            <label for="uname">Username:</label>
            <input type="text" name="uname" id="uname"
                   value="<?= esc(old('uname', $employee['username'] ?? '')) ?>" required />

            <label for="department">Department:</label>
            <select name="department" id="department" required>
                <option value="">Select department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= esc($department) ?>"
                        <?= old('department', $employee['department'] ?? '') === $department ? 'selected' : '' ?>>
                        <?= esc($department) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Save Changes</button>
        </form>

        <p class="form-note">
            <a href="<?= site_url('admin') ?>">Back to Admin Panel</a>
        </p>
    </div>

</body>
</html>
