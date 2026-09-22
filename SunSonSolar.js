const form = document.querySelector("form");
form.addEventListener("submit", function(event) {
event.preventDefault();

const fname = document.getElementById("fname").value;
const lname = document.getElementById("lname").value;
const Mname = document.getElementById("mname").value;
const email = document.getElementById("email").value;
const uname = document.getElementById("uname").value;
const password = document.getElementById("password").value;
const cpassword = document.getElementById("cpassword").value;

const specialPattern = /[^A-Za-z0-9]/;
const numberPattern = /[0-9]/;
const lowercasePattern = /[a-z]/;
const namePattern = /^[A-Za-z]+$/;
const uppercasePattern = /[A-Z]/;
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

 console.log(fname);

 if (fname === "") {
 alert("Please enter your first name.");
 return;

 }

 if (!namePattern.test(fname)) {
 alert("Please enter a valid first name (letters only).");
 return;
 }

 if (lname === "") {
 alert("Please enter your last name.");
 return;
 }

 if (!namePattern.test(lname)) {
 alert("Please enter a valid last name (letters only).");
 return;
 }

 if (!namePattern.test(Mname)) {
 alert("Please enter a valid middle name (letters only).");
 return;
 }

 if (email === "") {
 alert("Please enter your email address.");
 return;
 }
 
if (!emailPattern.test(email)) {
 alert("Please enter a valid email address.");
 return;
 }

if (/\s/.test(uname)) {
    alert("Username cannot contain spaces.");
    return;
}
 

if (password === "") {
 alert("Please enter your password.");
 return;
 }  

if (password.length < 8) {
 alert("Password must be at least 8 characters long.");
 return;
 }

if (!uppercasePattern.test(password)) {
    alert("Password must contain at least one uppercase letter.");
    return;
}

if (!lowercasePattern.test(password)) {
    alert("Password must contain at least one lowercase letter.");
    return;
}

if (!numberPattern.test(password)) {
    alert("Password must contain at least one number.");
    return;
}

if (!specialPattern.test(password)) {
    alert("Password must contain at least one special character.");
    return;
}

if (cpassword === "") {
    alert("Please confirm your password.");
    return;
}
if (password !== cpassword) {
    alert("Passwords do not match.");
    return;
}

});