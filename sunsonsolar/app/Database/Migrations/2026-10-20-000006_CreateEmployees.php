<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployees extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'middle_name' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'birth_date' => ['type' => 'DATE'],
            'gender' => ['type' => 'ENUM', 'constraint' => ['Male', 'Female'], 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20],
            'address' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'department_id' => ['type' => 'INT', 'constraint' => 11],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->addUniqueKey('email', 'uq_employee_email');
        $this->forge->addUniqueKey('username', 'uq_employee_username');
        $this->forge->addForeignKey('department_id', 'departments', 'department_id', '', '', 'fk_employee_department');
        $this->forge->createTable('employees', true);
    }

    public function down()
    {
        $this->forge->dropTable('employees', true);
    }
}
