<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>120],
            'email' => ['type'=>'VARCHAR','constraint'=>190],
            'password_hash' => ['type'=>'VARCHAR','constraint'=>255],
            'role' => ['type'=>'ENUM','constraint'=>['admin','user'],'default'=>'user'],
            'status' => ['type'=>'ENUM','constraint'=>['active','inactive'],'default'=>'active'],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);

        $this->db->table('users')->insert([
            'name' => 'Lex Factum Administrator',
            'email' => 'admin@lexfactum.local',
            'password_hash' => password_hash('ChangeMe@123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
