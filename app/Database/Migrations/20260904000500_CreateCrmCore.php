<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateCrmCore extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true], 'name'=>['type'=>'VARCHAR','constraint'=>160],
            'email'=>['type'=>'VARCHAR','constraint'=>190,'null'=>true], 'phone'=>['type'=>'VARCHAR','constraint'=>30,'null'=>true],
            'alternate_phone'=>['type'=>'VARCHAR','constraint'=>30,'null'=>true], 'address'=>['type'=>'TEXT','null'=>true],
            'city'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true], 'state'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'notes'=>['type'=>'TEXT','null'=>true], 'status'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'active'],
            'created_at'=>['type'=>'DATETIME','null'=>true], 'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]); $this->forge->addKey('id',true); $this->forge->createTable('clients',true);
        $this->forge->addField([
            'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true], 'client_id'=>['type'=>'BIGINT','unsigned'=>true],
            'case_number'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true], 'title'=>['type'=>'VARCHAR','constraint'=>200],
            'court'=>['type'=>'VARCHAR','constraint'=>180,'null'=>true], 'case_type'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'filing_date'=>['type'=>'DATE','null'=>true], 'status'=>['type'=>'VARCHAR','constraint'=>30,'default'=>'open'],
            'description'=>['type'=>'TEXT','null'=>true], 'created_at'=>['type'=>'DATETIME','null'=>true], 'updated_at'=>['type'=>'DATETIME','null'=>true],
        ]); $this->forge->addKey('id',true); $this->forge->addKey('client_id'); $this->forge->createTable('cases',true);
    }
    public function down(): void { $this->forge->dropTable('cases',true); $this->forge->dropTable('clients',true); }
}
