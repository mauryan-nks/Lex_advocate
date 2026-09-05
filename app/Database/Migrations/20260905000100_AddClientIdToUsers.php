<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientIdToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('client_id', 'users')) {
            $this->forge->addColumn('users', [
                'client_id' => ['type'=>'INT','unsigned'=>true,'null'=>true,'after'=>'status'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('client_id', 'users')) {
            $this->forge->dropColumn('users', 'client_id');
        }
    }
}
