<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSocialMedia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'platform' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->createTable('social_media', true);
    }

    public function down()
    {
        $this->forge->dropTable('social_media', true);
    }
}
