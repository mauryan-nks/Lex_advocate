<?php
namespace App\Controllers;

use App\Models\LegalUpdateModel;
use CodeIgniter\Controller;

class Updates extends Controller
{
    public function index()
    {
        $model = new LegalUpdateModel();
        $updates = $model->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('pages/updates', ['updates' => $updates]);
    }

    public function show(string $slug)
    {
        $update = (new LegalUpdateModel())
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$update) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('pages/update-detail', ['update' => $update]);
    }
}
