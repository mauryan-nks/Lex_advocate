<?php
namespace App\Models;
use CodeIgniter\Model;
class CaseModel extends Model
{
    protected $table = 'cases'; protected $primaryKey = 'id'; protected $returnType = 'array';
    protected $allowedFields = ['client_id','case_number','title','court','case_type','filing_date','status','description'];
    protected $useTimestamps = true;
}
