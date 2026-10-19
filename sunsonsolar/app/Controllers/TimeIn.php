<?php

namespace App\Controllers;

use App\Models\AttendanceLogModel;

class TimeIn extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'employee') {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Employee access only.');
        }

        return view('timein');
    }


public function save()
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to(site_url('login'));
    }

    if (session()->get('role') !== 'employee') {
        return redirect()->to(site_url('dashboard'))
            ->with('error', 'Employee access only.');
    }

    $employeeId = session()->get('user_id');
    $action = $this->request->getPost('action');
    $model = new AttendanceLogModel();

    if ($action === 'time_in') {
        // Check kung may active attendance pa
        $activeLog = $model
            ->where('employee_id', $employeeId)
            ->where('time_out', null)
            ->first();

        if ($activeLog) {
            return redirect()->to(site_url('timein'))
                ->with('error', 'You are already timed in.');
        }

        $model->insert([
            'employee_id' => $employeeId,
            'time_in' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('timein'))
            ->with('success', 'Time In recorded successfully.');
    }

    if ($action === 'time_out') {
        $activeLog = $model
            ->where('employee_id', $employeeId)
            ->where('time_out', null)
            ->orderBy('time_in', 'DESC')
            ->first();

        if (!$activeLog) {
            return redirect()->to(site_url('timein'))
                ->with('error', 'You need to Time In first.');
        }

        $model->update($activeLog['id'], [
        'time_out' => date('Y-m-d H:i:s'),
       ]);

        if ($model->errors()) {
           return redirect()->to(site_url('timein'))
        ->with('error', 'Unable to record Time Out.');
       }

        return redirect()->to(site_url('timein'))
        ->with('success', 'Time Out recorded successfully.');

      }
   }
}
