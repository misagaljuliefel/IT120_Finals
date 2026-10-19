<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactInfo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'address' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'office_hours' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->createTable('contact_info', true);
    }

    public function down()
    {
        $this->forge->dropTable('contact_info', true);
    }
}
