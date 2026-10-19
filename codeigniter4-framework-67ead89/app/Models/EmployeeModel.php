<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'first_name',
        'last_name',
        'middle_name',
        'birth_date',
        'gender',
        'email',
        'phone',
        'address',
        'username',
        'password',
        'role',
    ];
}
