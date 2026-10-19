<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\EmployeeModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $username = trim((string) $this->request->getPost('uname'));
        $password = (string) $this->request->getPost('password');

        if ($username === '' || $password === '') {
            return redirect()->back()
                ->with('error', 'Please enter your username and password.');
        }

        $customerModel = new CustomerModel();
        $employeeModel = new EmployeeModel();

        // Check customer account first.
        $customer = $customerModel
            ->where('username', $username)
            ->first();

        if ($customer && password_verify($password, $customer['password'])) {
            session()->regenerate();

            session()->set([
                'user_id'    => $customer['id'],
                'username'   => $customer['username'],
                'role'       => 'customer',
                'isLoggedIn' => true,
            ]);

            return redirect()->to(site_url('dashboard'));
        }

        // Check employee or admin account.
        $employee = $employeeModel
            ->where('username', $username)
            ->first();

        if ($employee && password_verify($password, $employee['password'])) {

            // Block inactive employee accounts.
            if (($employee['status'] ?? 'active') !== 'active') {
                return redirect()->back()
                    ->with('error', 'This account is inactive. Please contact the administrator.');
            }

            session()->regenerate();

            session()->set([
                'user_id'    => $employee['id'],
                'username'   => $employee['username'],
                'role'       => $employee['role'],
                'isLoggedIn' => true,
            ]);

            return redirect()->to(site_url('dashboard'));
        }

        return redirect()->back()
            ->with('error', 'Incorrect username or password.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
