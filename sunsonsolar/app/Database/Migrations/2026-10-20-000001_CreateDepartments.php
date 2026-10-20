<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'department_id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'department_name' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('department_id', true); // primary key
        $this->forge->createTable('departments', true);
    }

    public function down()
    {
        $this->forge->dropTable('departments', true);
    }
}
