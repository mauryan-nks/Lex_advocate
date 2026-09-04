<?php

namespace App\Models;

use CodeIgniter\Model;

class PracticeAreaModel extends Model
{
    protected $table = 'practice_areas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'slug', 'short_description', 'content', 'image', 'is_active', 'sort_order'];
    protected $useTimestamps = true;
}
