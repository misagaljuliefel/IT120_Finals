<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
    <div class="container">
        <h1>Sun Son Solar</h1>
        <h2>Create an Account</h2>

        <!-- This is what shows the Success or Error messages! -->
        <?php if (session()->getFlashdata('success')): ?>
            <p class="form-note" style="color: #4CAF50; font-weight: bold;">
                <?= esc(session()->getFlashdata('success')) ?>
            </p>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <p class="form-note" style="color: #f44336; font-weight: bold;">
                <?= esc(session()->getFlashdata('error')) ?>
            </p>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <ul style="color: #f44336; text-align: left; font-size: 14px; list-style: none; padding: 0;">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="<?= site_url('registration/client') ?>">
            <!-- Required for CodeIgniter security -->
            <?= csrf_field() ?>

            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" required/>

            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" required/>

            <label for="mname">Middle Name:</label>
            <input type="text" name="mname" id="mname" />
            
            <label for="bdate">Birth Date:</label>
            <input type="date" name="bdate" id="bdate" required/>
            
            <label>Gender:</label>
            <div class="gender-options">
            <label><input type="radio" name="gender" value="Male" required /> Male</label>
            <label><input type="radio" name="gender" value="Female" /> Female</label>
            </div>
            
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" id="phone" placeholder="09123456789" required />
            
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" placeholder="123 Street, City, Country" required />

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required/>

            <label for="uname">Username:</label>
            <input type="text" name="uname" id="uname" required/>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required/>

            <label for="cpassword">Confirm Password:</label>
            <input type="password" name="cpassword" id="cpassword" required/>
            
            <button type="submit">Register</button>
        </form>

        <p class="form-note">Already have an account? <a href="<?= site_url('login') ?>">Log in</a></p>
    </div>
<?= $this->endSection() ?>