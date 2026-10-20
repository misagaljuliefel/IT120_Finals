<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
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
    ];
}
