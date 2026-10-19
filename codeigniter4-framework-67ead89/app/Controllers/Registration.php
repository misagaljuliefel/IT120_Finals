<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Registration extends BaseController
{
    public function index()
    {
        return redirect()->to(site_url('registration/client'));
    }

    public function client()
    {
        return view('client_registration');
    }

    public function registerClient()
    {
        $rules = [
            'fname' => [
                'label' => 'First name',
                'rules' => 'required|regex_match[/^[a-zA-Z\s\-]+$/]',
            ],
            'lname' => [
                'label' => 'Last name',
                'rules' => 'required|regex_match[/^[a-zA-Z\s\-]+$/]',
            ],
            'mname' => [
                'label' => 'Middle name',
                'rules' => 'permit_empty|regex_match[/^[a-zA-Z\s\-]+$/]',
            ],
            'bdate' => [
                'label' => 'Birthdate',
                'rules' => 'required|valid_date[Y-m-d]',
            ],
            'gender' => [
                'label' => 'Gender',
                'rules' => 'required|in_list[Male,Female]',
            ],
            'phone' => [
                'label' => 'Phone number',
                'rules' => 'required|regex_match[/^09[0-9]{9}$/]',
            ],
            'address' => [
                'label' => 'Address',
                'rules' => 'required|min_length[5]|max_length[255]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[255]',
            ],
            'uname' => [
                'label' => 'Username',
                'rules' => 'required|alpha_numeric|min_length[4]|max_length[50]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => [
                    'required',
                    'min_length[8]',
                    'regex_match[/[A-Z]/]',
                    'regex_match[/[a-z]/]',
                    'regex_match[/[0-9]/]',
                    'regex_match[/[^a-zA-Z0-9]/]',
                ],
            ],
            'cpassword' => [
                'label' => 'Confirm password',
                'rules' => 'required|matches[password]',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $birthdate = $this->request->getPost('bdate');

        if ($birthdate >= date('Y-m-d')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Birthdate must be earlier than today.');
        }

        $username = trim($this->request->getPost('uname'));
        $email    = trim($this->request->getPost('email'));

        $customers = new CustomerModel();
        $employees = new \App\Models\EmployeeModel();

        if (
            $customers->usernameExists($username) ||
            $employees->where('username', $username)->countAllResults() > 0
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username is already taken.');
        }

        if (
            $customers->emailExists($email) ||
            $employees->where('email', $email)->countAllResults() > 0
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email is already registered.');
        }

        $customers->insert([
            'first_name' => trim($this->request->getPost('fname')),
            'last_name'  => trim($this->request->getPost('lname')),
            'middle_name'=> trim($this->request->getPost('mname') ?? ''),
            'birth_date' => $birthdate,
            'gender'     => $this->request->getPost('gender'),
            'phone'      => trim($this->request->getPost('phone')),
            'address'    => trim($this->request->getPost('address')),
            'email'      => $email,
            'username'   => $username,
            'password'   => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
        ]);

        return redirect()->to(site_url('login'))
            ->with('success', 'Registration successful. You may now log in.');
    }
}
