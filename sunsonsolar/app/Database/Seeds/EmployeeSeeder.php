<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // Passwords are hashed here. Change them after handover.
        $this->db->table('employees')->insertBatch([
            [
                'id'            => 1,
                'first_name'    => 'Katherine',
                'middle_name'   => 'Olap',
                'last_name'     => 'Sinagaraw',
                'birth_date'    => '1990-07-01',
                'gender'        => null,
                'email'         => 'katherine.sinagaraw@sunsonsolar.com',
                'phone'         => '09291230983',
                'address'       => null,
                'username'      => 'KittyKat16',
                'password'      => password_hash('K@tSunShine16', PASSWORD_DEFAULT),
                'department_id' => 1,
            ],
            [
                'id'            => 2,
                'first_name'    => 'Sol',
                'middle_name'   => 'Sun',
                'last_name'     => 'Solis',
                'birth_date'    => '1967-01-08',
                'gender'        => null,
                'email'         => 'sol.solis', // incomplete in the interview
                'phone'         => '09291230983',
                'address'       => null,
                'username'      => 'admin',
                'password'      => password_hash('admin123', PASSWORD_DEFAULT),
                'department_id' => 2,
            ],
        ]);
    }
}
