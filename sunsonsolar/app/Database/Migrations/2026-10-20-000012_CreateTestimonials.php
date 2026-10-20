<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTestimonials extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'customer_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'testimonial' => ['type' => 'TEXT', 'null' => true],
            'image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'date_created' => ['type' => 'DATETIME', 'null' => true, 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->createTable('testimonials', true);
    }

    public function down()
    {
        $this->forge->dropTable('testimonials', true);
    }
}
