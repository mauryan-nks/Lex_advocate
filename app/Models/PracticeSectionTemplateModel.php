<?php

namespace App\Models;

use CodeIgniter\Model;

class PracticeSectionTemplateModel extends Model
{
    protected $table = 'cms_section_templates';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'section_type', 'data', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
