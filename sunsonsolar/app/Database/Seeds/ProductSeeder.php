<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('products')->insertBatch([
            ['id' => 1, 'name' => 'Solar Panels', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Inverters', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Batteries', 'status' => 'Active'],
            ['id' => 4, 'name' => 'Racking and Mounting', 'status' => 'Active'],
            ['id' => 5, 'name' => 'Wires', 'status' => 'Active'],
        ]);
    }
}
