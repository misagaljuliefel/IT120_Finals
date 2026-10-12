<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Login</title>
    <link rel="stylesheet" href="<?= base_url('assets/SunSonSolar.css') ?>" />
</head>
<body>

    <div class="container">
        <h1>Sun Son Solar</h1>
        
        <h2>Log In</h2>

        <?php if (session()->getFlashdata('success')): ?>
         <p class="form-note">
        <?= esc(session()->getFlashdata('success')) ?>
         </p>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <p class="form-note">
        <?= esc(session()->getFlashdata('error')) ?>
        </p>
        <?php endif; ?>


        
     <form id="loginForm" action="<?= site_url('login') ?>" method="post">
     <?= csrf_field() ?>

     <label for="uname">Username:</label>
     <input type="text" name="uname" id="uname" required />

     <label for="password">Password:</label>
     <input type="password" name="password" id="password" required />

     <button type="submit">Log In</button>
     </form>

        <p class="form-note">
            New customer?
        <a href="<?= site_url('registration/client') ?>">Register here</a>
        </p>
    </div>  

    
</body>
</html>
