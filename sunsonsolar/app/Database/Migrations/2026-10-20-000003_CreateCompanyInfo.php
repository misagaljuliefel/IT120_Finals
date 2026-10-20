<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompanyInfo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'company_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'about_us' => ['type' => 'TEXT', 'null' => true],
            'mission' => ['type' => 'TEXT', 'null' => true],
            'vision' => ['type' => 'TEXT', 'null' => true],
            'logo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->createTable('company_info', true);
    }

    public function down()
    {
        $this->forge->dropTable('company_info', true);
    }
}
