<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sun Son Solar - Registration</title>
    <link rel="stylesheet" href="/assets/SunSonSolar.css">
</head>
<body>

    <div class="container">
        <h1>Sun Son Solar</h1>
        <h2>Create an Account</h2>

        <form>
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
            
            <label for="department">Department:</label>
            <select name="department" id="department" required>
                <option value="" disabled selected>Select department</option>
                <option value="Technical & Dispatch">Technical & Dispatch</option>
                <option value="Installation & Maintenance">Installation & Maintenance</option>
                <option value="Sales & Customer Relations">Sales & Customer Relations</option>
                <option value="Administration & Finance">Administration & Finance</option>
                <option value="IT">IT</option>
            </select>
            
            <button type="submit">Register</button>
            
        </form>

        <p class="form-note">Already have an account? <a href="login.html">Log in</a></p>
    </div>
    <script src="<?= base_url('assets/SunSonSolar.js') ?>"></script>

</body>
</html>
