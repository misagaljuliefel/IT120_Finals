<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('departments')->insertBatch([
            ['department_id' => 1, 'department_name' => 'Administration'],
            ['department_id' => 2, 'department_name' => 'Information Technology'],
            ['department_id' => 3, 'department_name' => 'Dispatch'],
            ['department_id' => 4, 'department_name' => 'Accounting'],
            ['department_id' => 5, 'department_name' => 'Human Resource'],
            ['department_id' => 6, 'department_name' => 'Marketing'],
            ['department_id' => 7, 'department_name' => 'Sales'],
            ['department_id' => 8, 'department_name' => 'Customer Service'],
        ]);
    }
}
