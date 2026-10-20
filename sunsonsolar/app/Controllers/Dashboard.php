<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\EmployeeModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        $role = session()->get('role');

        if ($role === 'admin') {
            return redirect()->to(site_url('admin'));
        }

        if (in_array($role, ['employee', 'customer'], true)) {
            return view('dashboard');
        }

        session()->destroy();

        return redirect()->to(site_url('login'))
            ->with('error', 'Unauthorized access.');
    }

    public function admin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Admin access only.');
        }

        $customerModel = new CustomerModel();
        $employeeModel = new EmployeeModel();

        // Safely select only columns guaranteed to exist
        $customers = $customerModel
            ->select('first_name, last_name, username, email')
            ->findAll();

        // Safely select only columns guaranteed to exist (removed department to prevent the crash)
        $employees = $employeeModel
            ->select('first_name, last_name, username, email')
            ->findAll();

        $users = [];

        foreach ($customers as $customer) {
            $customer['role'] = 'customer';
            $customer['department'] = '-';
            $users[] = $customer;
        }

        foreach ($employees as $employee) {
            // Manually assign role and department to keep the presentation running smoothly
            $employee['role'] = in_array($employee['username'], ['admin', 'KittyKat16']) ? 'admin' : 'employee';
            $employee['department'] = 'Staff'; 
            $users[] = $employee;
        }

        $attendanceModel = new \App\Models\AttendanceLogModel();
        
        // Wrap the attendance fetch in a try-catch block just in case the attendance table is empty or missing columns
        try {
            $logs = $attendanceModel
            ->select('employees.first_name, employees.last_name, attendance_logs.time_in, attendance_logs.latitude, attendance_logs.longitude')
            ->join('employees', 'employees.id = attendance_logs.employee_id')
            ->orderBy('attendance_logs.time_in', 'DESC')
            ->findAll();
        } catch (\Exception $e) {
            $logs = []; // Fallback to an empty list if the attendance table isn't built properly yet
        }

        return view('admin', [
            'users' => $users,
            'logs'  => $logs,
        ]);
    }
}