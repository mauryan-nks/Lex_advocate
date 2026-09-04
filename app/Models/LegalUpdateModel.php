<?php

namespace App\Models;

use CodeIgniter\Model;

class LegalUpdateModel extends Model
{
    protected $table = 'legal_updates';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'slug', 'excerpt', 'content', 'category', 'status', 'published_at', 'builder_json'];
    protected $useTimestamps = true;
}
