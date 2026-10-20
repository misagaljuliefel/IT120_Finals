<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Order matters: departments must exist before employees.
        $this->call('DepartmentSeeder');
        $this->call('ProductSeeder');
        $this->call('ServiceSeeder');
        $this->call('EmployeeSeeder');
    }
}
