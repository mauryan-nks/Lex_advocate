<?php
namespace App\Models;
use CodeIgniter\Model;
class EnquiryModel extends Model
{
    protected $table = 'enquiries';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['full_name','email','phone','practice_area','message','type','status','preferred_date','preferred_time'];
    protected $useTimestamps = true;
}
