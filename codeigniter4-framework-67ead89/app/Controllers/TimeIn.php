<?php

namespace App\Controllers;

use App\Models\AttendanceLogModel;

class TimeIn extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
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
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'employee') {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Employee access only.');
        }

        $action = (string) $this->request->getPost('action');

        // Reject any action other than Time In or Time Out.
        if (! in_array($action, ['time_in', 'time_out'], true)) {
            return redirect()->to(site_url('timein'))
                ->with('error', 'Invalid attendance action.');
        }

        $employeeId = session()->get('user_id');
        $model = new AttendanceLogModel();

        if ($action === 'time_in') {
            $activeLog = $model
                ->where('employee_id', $employeeId)
                ->where('time_out', null)
                ->first();

            if ($activeLog) {
                return redirect()->to(site_url('timein'))
                    ->with('error', 'You are already timed in.');
            }

            $saved = $model->insert([
                'employee_id' => $employeeId,
                'time_in' => date('Y-m-d H:i:s'),
            ]);

            if ($saved === false) {
                log_message('error', 'Time In failed: {errors}', [
                    'errors' => json_encode($model->errors()),
                ]);

                return redirect()->to(site_url('timein'))
                    ->with('error', 'Unable to record Time In.');
            }

            return redirect()->to(site_url('timein'))
                ->with('success', 'Time In recorded successfully.');
        }

        $activeLog = $model
            ->where('employee_id', $employeeId)
            ->where('time_out', null)
            ->orderBy('time_in', 'DESC')
            ->first();

        if (! $activeLog) {
            return redirect()->to(site_url('timein'))
                ->with('error', 'You need to Time In first.');
        }

        $saved = $model->update($activeLog['id'], [
            'time_out' => date('Y-m-d H:i:s'),
        ]);

        if ($saved === false) {
            log_message('error', 'Time Out failed: {errors}', [
                'errors' => json_encode($model->errors()),
            ]);

            return redirect()->to(site_url('timein'))
                ->with('error', 'Unable to record Time Out.');
        }

        return redirect()->to(site_url('timein'))
            ->with('success', 'Time Out recorded successfully.');
    }
}
