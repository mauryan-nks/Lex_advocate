<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddEnquiryManagement extends Migration
{
    public function up(): void
    {
        $fields = [];
        if (! $this->db->fieldExists('type', 'enquiries')) {
            $fields['type'] = ['type'=>'VARCHAR','constraint'=>30,'default'=>'contact','after'=>'practice_area'];
        }
        if (! $this->db->fieldExists('preferred_date', 'enquiries')) {
            $fields['preferred_date'] = ['type'=>'DATE','null'=>true,'after'=>'message'];
        }
        if (! $this->db->fieldExists('preferred_time', 'enquiries')) {
            $fields['preferred_time'] = ['type'=>'TIME','null'=>true,'after'=>'preferred_date'];
        }
        if ($fields) $this->forge->addColumn('enquiries', $fields);
    }
    public function down(): void
    {
        $drop=[];
        foreach(['type','preferred_date','preferred_time'] as $f) if($this->db->fieldExists($f,'enquiries')) $drop[]=$f;
        if($drop) $this->forge->dropColumn('enquiries',$drop);
    }
}
