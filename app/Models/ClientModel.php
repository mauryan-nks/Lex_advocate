<?php
namespace App\Models;
use CodeIgniter\Model;
class ClientModel extends Model
{
    protected $table = 'clients'; protected $primaryKey = 'id'; protected $returnType = 'array';
    protected $allowedFields = ['name','email','phone','alternate_phone','address','city','state','notes','status'];
    protected $useTimestamps = true;
}
