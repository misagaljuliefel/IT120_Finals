const form = document.querySelector("form");
form.addEventListener("submit", function(event) {
event.preventDefault();

const fname = document.getElementById("fname").value;
const lname = document.getElementById("lname").value;
const mname = document.getElementById("mname").value;
const email = document.getElementById("email").value;
const uname = document.getElementById("uname").value;
const phone = document.getElementById("phone").value;
const bdate = document.getElementById("bdate").value;
const password = document.getElementById("password").value;
const cpassword = document.getElementById("cpassword").value;


const today = new Date().toISOString().split("T")[0];
const specialPattern = /[^A-Za-z0-9]/;
const numberPattern = /[0-9]/;
const lowercasePattern = /[a-z]/;
const namePattern = /^[A-Za-z]+$/;
const uppercasePattern = /[A-Z]/;
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const departmentPattern = /^[A-Za-z ]+$/;

 console.log(fname);


 if (!namePattern.test(fname)) {
 alert("Please enter a valid first name (letters only).");
 return;
 }


 if (!namePattern.test(lname)) {
 alert("Please enter a valid last name (letters only).");
 return;
 }

if (mname !== "" && !namePattern.test(mname)) {
    alert("Please enter a valid middle name (letters only).");
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

if (bdate > today) {
    alert("Birth date cannot be in the future.");
    return;
}

if (!/^09[0-9]{9}$/.test(phone)) {
    alert("Please enter a valid phone number.");
    return;
}




});
