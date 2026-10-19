<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLegalNotices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'content' => ['type' => 'LONGTEXT', 'null' => true],
            'last_updated' => ['type' => 'DATE', 'null' => true],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->createTable('legal_notices', true);
    }

    public function down()
    {
        $this->forge->dropTable('legal_notices', true);
    }
}
