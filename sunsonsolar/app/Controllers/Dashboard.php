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

        $customers = $customerModel
            ->select('first_name, last_name, username, email')
            ->findAll();

        // EMERGENCY PATCH: Removed 'role' from the select statement to prevent the crash
        $employees = $employeeModel
            ->select('first_name, last_name, username, email, department')
            ->findAll();

        $users = [];

        foreach ($customers as $customer) {
            $customer['role'] = 'customer';
            $customer['department'] = '-';
            $users[] = $customer;
        }

        foreach ($employees as $employee) {
            // EMERGENCY PATCH: Assign role manually for the admin table
            $employee['role'] = in_array($employee['username'], ['admin', 'KittyKat16']) ? 'admin' : 'employee';
            $users[] = $employee;
        }


        $attendanceModel = new \App\Models\AttendanceLogModel();
        $logs = $attendanceModel
        ->select('employees.first_name, employees.last_name,
              attendance_logs.time_in,
              attendance_logs.latitude,
              attendance_logs.longitude')
        ->join('employees', 'employees.id = attendance_logs.employee_id')
        ->orderBy('attendance_logs.time_in', 'DESC')
        ->findAll();

          return view('admin', [
          'users' => $users,
          'logs'  => $logs,
        ]);

    }
}