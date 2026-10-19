<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\EmployeeModel;
use App\Models\AttendanceLogModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
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
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Admin access only.');
        }

        $customerModel = new CustomerModel();
        $employeeModel = new EmployeeModel();

        $customers = $customerModel
            ->select('id, first_name, last_name, username, email')
            ->findAll();

        $employees = $employeeModel
            ->select('id, first_name, last_name, username, role, email, department, status')
            ->findAll();

        $users = [];

        foreach ($customers as $customer) {
            $customer['role'] = 'customer';
            $customer['department'] = '-';
            $customer['status'] = 'active';
            $users[] = $customer;
        }

        foreach ($employees as $employee) {
            $users[] = $employee;
        }

        $attendanceModel = new AttendanceLogModel();

        $logs = $attendanceModel
            ->select(
                'employees.first_name, employees.last_name,
                attendance_logs.time_in,
                attendance_logs.latitude,
                attendance_logs.longitude'
            )
            ->join('employees', 'employees.id = attendance_logs.employee_id')
            ->orderBy('attendance_logs.time_in', 'DESC')
            ->findAll();

        return view('admin', [
            'users' => $users,
            'logs'  => $logs,
        ]);
    }
}
