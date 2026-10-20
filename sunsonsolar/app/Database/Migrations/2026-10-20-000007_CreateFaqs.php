<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFaqs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'question' => ['type' => 'VARCHAR', 'constraint' => 255],
            'answer' => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->createTable('faqs', true);
    }

    public function down()
    {
        $this->forge->dropTable('faqs', true);
    }
}
