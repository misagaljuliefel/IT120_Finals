<?php

namespace App\Controllers;

class Registration extends BaseController
{
    public function index()
    {
        return view('registration');
    }

    public function client()
    {
        return view('client_registration');
    }

    public function registerClient()
    {
        
        $fname = $this->request->getPost('fname');
        $lname = $this->request->getPost('lname');
        $mname = $this->request->getPost('mname');
        $bdate = $this->request->getPost('bdate');
        $gender = $this->request->getPost('gender');
        $phone = $this->request->getPost('phone');
        $address = $this->request->getPost('address');
        $email = $this->request->getPost('email');
        $uname = $this->request->getPost('uname');
        $password = $this->request->getPost('password');
        $cpassword = $this->request->getPost('cpassword');

    //First Name
    if (!preg_match('/^[A-Za-z]+$/', $fname)) {
        return "Invalid first name";
    }

    // Last Name
    if (!preg_match('/^[A-Za-z]+$/', $lname)) {
        return "Invalid last name";
    }

    // Middle Name 
    if ($mname !== "" && !preg_match('/^[A-Za-z]+$/', $mname)) {
        return "Invalid middle name";
    }

    // Birth Date
    if ($bdate > date('Y-m-d')) {
        return "Birth date cannot be in the future";
    }

    // Phone Number
    if (!preg_match('/^09[0-9]{9}$/', $phone)) {
        return "Invalid phone number";
    }

    // Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address";
    }

    // Username
    if (preg_match('/\s/', $uname)) {
        return "Username cannot contain spaces";
    }

    // Password 
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain an uppercase letter";
    }

    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain a lowercase letter";
    }

    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain a number";
    }

    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return "Password must contain a special character";
    }

    if ($password !== $cpassword) {
        return "Passwords do not match";    
    }
    
    $password = password_hash($password, PASSWORD_DEFAULT);

     $db = \Config\Database::connect();

     $sql = "INSERT INTO customers 
        (first_name, last_name, middle_name, birth_date, gender, email, phone, address, username, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $db->query($sql, [
      $fname,
      $lname,
      $mname,
      $bdate,
      $gender,
      $email,
      $phone,
      $address,
      $uname,
      $password
     ]);

     return "Registration saved successfully";

    }
    public function updateEmployeePassword()
    {
        $username = $this->request->getPost('username');
        $newPassword = $this->request->getPost('password');

        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $db = \Config\Database::connect();

        $sql = "UPDATE employees
                SET password = ?
                WHERE username = ?";

        $db->query($sql, [
            $newPassword,
            $username
        ]);

        return view('test_password');
    }
}