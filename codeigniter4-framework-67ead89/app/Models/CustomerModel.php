<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

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

    protected $useTimestamps = false;

    public function usernameExists(string $username): bool
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }

    public function emailExists(string $email): bool
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }
}
