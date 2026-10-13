<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceLogModel extends Model
{
    protected $table            = 'attendance_logs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;


    protected $allowedFields = [
    'employee_id',
    'time_in',
    'time_out',
    'latitude',
    'longitude',
    ];


    protected $useTimestamps = false;
}
