<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'middle_name' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'birth_date' => ['type' => 'DATE'],
            'gender' => ['type' => 'ENUM', 'constraint' => ['Male', 'Female']],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20],
            'address' => ['type' => 'VARCHAR', 'constraint' => 255],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true); // primary key
        $this->forge->addUniqueKey('email', 'uq_customer_email');
        $this->forge->addUniqueKey('username', 'uq_customer_username');
        $this->forge->createTable('customers', true);
    }

    public function down()
    {
        $this->forge->dropTable('customers', true);
    }
}
