<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('services')->insertBatch([
            ['id' => 1, 'title' => 'Consultation'],
            ['id' => 2, 'title' => 'Designing'],
            ['id' => 3, 'title' => 'Permitting'],
            ['id' => 4, 'title' => 'Installation'],
            ['id' => 5, 'title' => 'Maintenance'],
            ['id' => 6, 'title' => 'Repair'],
            ['id' => 7, 'title' => 'Monitoring'],
        ]);
    }
}
