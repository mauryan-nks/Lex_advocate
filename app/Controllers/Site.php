<?php
namespace App\Controllers;

use App\Models\PracticeAreaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Controller;

class Site extends Controller
{
    private array $pages = [
        'home'=>'index','about'=>'about','practice-areas'=>'practice-areas','updates'=>'updates','faq'=>'faq','contact'=>'contact','appointment'=>'appointment','bns-2023-explained'=>'bns-2023-explained',
    ];

    public function index(): string
    {
        $practiceAreas=(new PracticeAreaModel())->where('is_active',1)->orderBy('sort_order','ASC')->findAll();
        return view('pages/index',['practiceAreas'=>$practiceAreas]);
    }

    public function show(string $slug): ResponseInterface|string
    {
        $practice=(new PracticeAreaModel())->where('slug',$slug)->where('is_active',1)->first();
        if($practice){
            $sections=json_decode((string)($practice['builder_json'] ?? ''),true);
            if(!is_array($sections) || !$sections){
                $sections=[['id'=>'legacy','type'=>'richtext','hidden'=>false,'data'=>['eyebrow'=>'Overview','title'=>'Clear advice begins with a clear factual picture.','content'=>(string)($practice['content'] ?? ''),'theme'=>'light']]];
            }
            return view('pages/practice-detail',['practice'=>$practice,'sections'=>$sections]);
        }
        $view=$this->pages[$slug]??null;
        if($view===null) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('pages/'.$view);
    }
}
