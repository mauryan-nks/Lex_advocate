<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLegalUpdateBuilder extends Migration
{
    public function up()
    {
        $fields = ['builder_json' => ['type'=>'LONGTEXT','null'=>true]];
        if (! $this->db->fieldExists('builder_json', 'legal_updates')) {
            $this->forge->addColumn('legal_updates', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('builder_json', 'legal_updates')) {
            $this->forge->dropColumn('legal_updates', 'builder_json');
        }
    }
}
