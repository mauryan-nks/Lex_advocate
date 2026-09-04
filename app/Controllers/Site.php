<?php
namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Controller;

class Site extends Controller
{
    private array $pages = [
        'home' => 'index',
        'about' => 'about',
        'practice-areas' => 'practice-areas',
        'updates' => 'updates',
        'faq' => 'faq',
        'contact' => 'contact',
        'appointment' => 'appointment',
        'bns-2023-explained' => 'bns-2023-explained',
        'divorce-separation' => 'divorce-separation',
        'grandparent-rights' => 'grandparent-rights',
        'trademark-infringement' => 'trademark-infringement',
        'domestic-violence' => 'domestic-violence',
        'criminal-cases' => 'criminal-cases',
        'economic-offence' => 'economic-offence',
        'property-tax' => 'property-tax',
        'child-custody' => 'child-custody',
    ];

    public function index(): string
    {
        return view('pages/index');
    }

    public function show(string $slug): ResponseInterface|string
    {
        $view = $this->pages[$slug] ?? null;
        if ($view === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('pages/' . $view);
    }
}
