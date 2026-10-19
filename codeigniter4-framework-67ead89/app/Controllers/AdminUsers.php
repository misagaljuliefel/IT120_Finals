<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\EmployeeModel;

class AdminUsers extends BaseController
{
    private function requireAdmin()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Admin access only.');
        }

        return null;
    }

    public function create()
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        return view('employee_registration');
    }

    public function store()
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        $data = [
            'first_name'  => trim((string) $this->request->getPost('fname')),
            'last_name'   => trim((string) $this->request->getPost('lname')),
            'middle_name' => trim((string) $this->request->getPost('mname')),
            'birth_date'  => trim((string) $this->request->getPost('bdate')),
            'gender'      => trim((string) $this->request->getPost('gender')),
            'phone'       => trim((string) $this->request->getPost('phone')),
            'address'     => trim((string) $this->request->getPost('address')),
            'email'       => trim((string) $this->request->getPost('email')),
            'username'    => trim((string) $this->request->getPost('uname')),
            'department'  => trim((string) $this->request->getPost('department')),
        ];

        $password  = (string) $this->request->getPost('password');
        $cpassword = (string) $this->request->getPost('cpassword');

        if (! $this->validateData($data, $this->employeeRules())) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        if (! $this->validBirthDate($data['birth_date'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Please enter a valid birth date that is not in the future.');
        }

        if (! $this->validPassword($password)) {
            return redirect()->back()->withInput()
                ->with('error', 'Password must be at least 8 characters and include uppercase, lowercase, a number, and a special character.');
        }

        if ($password !== $cpassword) {
            return redirect()->back()->withInput()
                ->with('error', 'Passwords do not match.');
        }

        $employees = new EmployeeModel();
        $customers = new CustomerModel();

        if (
            $employees->where('username', $data['username'])->first() ||
            $customers->where('username', $data['username'])->first()
        ) {
            return redirect()->back()->withInput()
                ->with('error', 'Username already exists.');
        }

        if (
            $employees->where('email', $data['email'])->first() ||
            $customers->where('email', $data['email'])->first()
        ) {
            return redirect()->back()->withInput()
                ->with('error', 'Email already exists.');
        }

        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        $data['role'] = 'employee';
        $data['status'] = 'active';

        if (! $employees->insert($data)) {
            log_message('error', 'Employee creation failed: {errors}', [
                'errors' => json_encode($employees->errors()),
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'Unable to create employee account.');
        }

        return redirect()->to(site_url('admin'))
            ->with('success', 'Employee account created successfully.');
    }

    public function edit($id)
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        $employee = (new EmployeeModel())->find($id);

        if (! $employee || ($employee['role'] ?? '') !== 'employee') {
            return redirect()->to(site_url('admin'))
                ->with('error', 'Employee account not found.');
        }

        return view('employee_edit', ['employee' => $employee]);
    }

    public function update($id)
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        $employees = new EmployeeModel();
        $customers = new CustomerModel();
        $employee = $employees->find($id);

        if (! $employee || ($employee['role'] ?? '') !== 'employee') {
            return redirect()->to(site_url('admin'))
                ->with('error', 'Employee account not found.');
        }

        // These names must match the name attributes in employee_edit.php.
        $data = [
            'first_name'  => trim((string) $this->request->getPost('first_name')),
            'last_name'   => trim((string) $this->request->getPost('last_name')),
            'middle_name' => trim((string) $this->request->getPost('middle_name')),
            'birth_date'  => trim((string) $this->request->getPost('birth_date')),
            'gender'      => trim((string) $this->request->getPost('gender')),
            'phone'       => trim((string) $this->request->getPost('phone')),
            'address'     => trim((string) $this->request->getPost('address')),
            'email'       => trim((string) $this->request->getPost('email')),
            'username'    => trim((string) $this->request->getPost('username')),
            'department'  => trim((string) $this->request->getPost('department')),
        ];

        $status = trim((string) $this->request->getPost('status'));

        if (! in_array($status, ['active', 'inactive'], true)) {
            return redirect()->back()->withInput()
                ->with('error', 'Invalid employee status.');
        }

        $data['status'] = $status;

        if (! $this->validateData($data, $this->employeeRules())) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        if (! $this->validBirthDate($data['birth_date'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Please enter a valid birth date that is not in the future.');
        }

        $existingUsername = $employees->where('username', $data['username'])
            ->where('id !=', $id)
            ->first();

        $customerUsername = $customers->where('username', $data['username'])
            ->first();

        if ($existingUsername || $customerUsername) {
            return redirect()->back()->withInput()
                ->with('error', 'Username already exists.');
        }

        $existingEmail = $employees->where('email', $data['email'])
            ->where('id !=', $id)
            ->first();

        $customerEmail = $customers->where('email', $data['email'])
            ->first();

        if ($existingEmail || $customerEmail) {
            return redirect()->back()->withInput()
                ->with('error', 'Email already exists.');
        }

        if (! $employees->update($id, $data)) {
            log_message('error', 'Employee update failed: {errors}', [
                'errors' => json_encode($employees->errors()),
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'Unable to update employee information.');
        }

        return redirect()->to(site_url('admin'))
            ->with('success', 'Employee information updated successfully.');
    }

    public function toggleStatus($id)
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        $employees = new EmployeeModel();
        $employee = $employees->find($id);

        if (! $employee || ($employee['role'] ?? '') !== 'employee') {
            return redirect()->to(site_url('admin'))
                ->with('error', 'Employee account not found.');
        }

        $newStatus = (($employee['status'] ?? 'active') === 'active')
            ? 'inactive'
            : 'active';

        if (! $employees->update($id, ['status' => $newStatus])) {
            log_message('error', 'Employee status update failed: {errors}', [
                'errors' => json_encode($employees->errors()),
            ]);

            return redirect()->to(site_url('admin'))
                ->with('error', 'Unable to change employee status.');
        }

        $message = $newStatus === 'active'
            ? 'Employee account reactivated.'
            : 'Employee account deactivated.';

        return redirect()->to(site_url('admin'))
            ->with('success', $message);
    }

    private function employeeRules(): array
    {
        return [
            'first_name'  => 'required|regex_match[/^[A-Za-z]+$/]',
            'last_name'   => 'required|regex_match[/^[A-Za-z]+$/]',
            'middle_name' => 'permit_empty|regex_match[/^[A-Za-z]+$/]',
            'birth_date'  => 'required|valid_date[Y-m-d]',
            'gender'      => 'required|in_list[Male,Female]',
            'phone'       => 'required|regex_match[/^09[0-9]{9}$/]',
            'address'     => 'required|max_length[255]',
            'email'       => 'required|valid_email|max_length[100]',
            'username'    => 'required|alpha_numeric|max_length[50]',
            'department'  => 'required|in_list[Administration,IT,Dispatch,Accounting,HR,Marketing,Sales,Customer Service]',
            'status'      => 'required|in_list[active,inactive]',
        ];
    }

    private function validBirthDate(string $value): bool
    {
        $date = \DateTime::createFromFormat('!Y-m-d', $value);

        return $date !== false
            && $date->format('Y-m-d') === $value
            && $date <= new \DateTime('today');
    }

    private function validPassword(string $password): bool
    {
        return strlen($password) >= 8
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[a-z]/', $password)
            && preg_match('/[0-9]/', $password)
            && preg_match('/[^A-Za-z0-9]/', $password);
    }
}
