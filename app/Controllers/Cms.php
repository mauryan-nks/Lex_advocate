<?php

namespace App\Controllers;

use App\Models\LegalUpdateModel;
use App\Models\PracticeAreaModel;
use App\Models\PracticeSectionTemplateModel;
use CodeIgniter\Controller;

class Cms extends Controller
{
    protected LegalUpdateModel $updates;
    protected PracticeAreaModel $practiceAreas;
    protected PracticeSectionTemplateModel $templates;

    public function __construct()
    {
        $this->updates = new LegalUpdateModel();
        $this->practiceAreas = new PracticeAreaModel();
        $this->templates = new PracticeSectionTemplateModel();
    }

    public function index()
    {
        return view('cms/index', [
            'updatesCount' => $this->updates->countAllResults(),
            'publishedCount' => $this->updates->where('status', 'published')->countAllResults(),
            'practiceCount' => $this->practiceAreas->countAllResults(),
        ]);
    }

    public function updates()
    {
        return view('cms/updates', ['items' => $this->updates->orderBy('id', 'DESC')->findAll()]);
    }

    public function updateCreate()
    {
        return view('cms/update_builder', ['item' => $this->blankUpdateBuilderItem(), 'templates' => $this->templates->orderBy('name')->findAll(), 'create' => true]);
    }

    public function updateStore()
    {
        $data = $this->updateData();
        if (! $this->validateUpdate($data)) {
            return redirect()->back()->withInput()->with('error', validation_list_errors());
        }
        $data['slug'] = $this->uniqueSlug($data['slug']);
        if ($data['status'] === 'published' && empty($data['published_at'])) $data['published_at'] = date('Y-m-d H:i:s');
        $this->updates->insert($data);
        return redirect()->to(site_url('admin/cms/updates'))->with('success', 'Legal update created.');
    }

    public function updateEdit(int $id)
    {
        $item = $this->updates->find($id);
        if (! $item) return redirect()->to(site_url('admin/cms/updates'))->with('error', 'Legal update not found.');
        return view('cms/update_builder', ['item' => $item, 'templates' => $this->templates->orderBy('name')->findAll(), 'create' => false]);
    }

    public function updateBuilderStore()
    {
        $data = $this->updateData();
        if (! $this->validateUpdate($data)) return redirect()->back()->withInput()->with('error', validation_list_errors());
        $data['slug'] = $this->uniqueSlug($data['slug']);
        if ($data['status'] === 'published' && empty($data['published_at'])) $data['published_at'] = date('Y-m-d H:i:s');
        $data['builder_json'] = $this->normaliseBuilder((string)$this->request->getPost('builder_json'));
        $data['content'] = $this->builderToLegacyContent($data['builder_json']);
        $id = $this->updates->insert($data, true);
        return redirect()->to(site_url('admin/cms/updates/edit/'.$id))->with('success', 'Legal update created.');
    }

    public function updateBuilderSave(int $id)
    {
        $item = $this->updates->find($id);
        if (! $item) return redirect()->to(site_url('admin/cms/updates'))->with('error', 'Legal update not found.');
        $data = $this->updateData();
        if (! $this->validateUpdate($data)) return redirect()->back()->withInput()->with('error', validation_list_errors());
        $data['slug'] = $this->uniqueSlug($data['slug'], $id);
        if ($data['status'] === 'published' && empty($data['published_at'])) $data['published_at'] = $item['published_at'] ?: date('Y-m-d H:i:s');
        if ($data['status'] !== 'published') $data['published_at'] = null;
        $data['builder_json'] = $this->normaliseBuilder((string)$this->request->getPost('builder_json'));
        $data['content'] = $this->builderToLegacyContent($data['builder_json']);
        $this->updates->update($id, $data);
        return redirect()->to(site_url('admin/cms/updates/edit/'.$id))->with('success', 'Legal update and layout saved.');
    }

    public function updateSave(int $id)
    {
        $item = $this->updates->find($id);
        if (! $item) return redirect()->to(site_url('admin/cms/updates'))->with('error', 'Legal update not found.');
        $data = $this->updateData();
        if (! $this->validateUpdate($data)) return redirect()->back()->withInput()->with('error', validation_list_errors());
        $data['slug'] = $this->uniqueSlug($data['slug'], $id);
        if ($data['status'] === 'published' && empty($data['published_at'])) $data['published_at'] = $item['published_at'] ?: date('Y-m-d H:i:s');
        if ($data['status'] !== 'published') $data['published_at'] = null;
        $this->updates->update($id, $data);
        return redirect()->to(site_url('admin/cms/updates'))->with('success', 'Legal update saved.');
    }

    public function updateDelete(int $id)
    {
        $this->updates->delete($id);
        return redirect()->to(site_url('admin/cms/updates'))->with('success', 'Legal update deleted.');
    }

    public function practices()
    {
        return view('cms/practices', ['items' => $this->practiceAreas->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll()]);
    }

    public function practiceCreate()
    {
        return view('cms/practice_builder', ['item' => $this->blankPracticeBuilderItem(), 'templates' => $this->templates->orderBy('name')->findAll(), 'create' => true]);
    }

    public function practiceBuilderStore()
    {
        $data = $this->practiceData();
        if (! $this->validatePractice($data)) return redirect()->back()->withInput()->with('error', validation_list_errors());
        $data['slug'] = $this->uniquePracticeSlug($data['slug']);
        $data['builder_json'] = $this->normaliseBuilder((string)$this->request->getPost('builder_json'));
        $data['content'] = $this->builderToLegacyContent($data['builder_json']);
        $id = $this->practiceAreas->insert($data, true);
        return redirect()->to(site_url('admin/cms/practice-areas/edit/'.$id))->with('success', 'Practice area created.');
    }

    public function practiceStore()
    {
        $data = $this->practiceData();
        if (! $this->validatePractice($data)) return redirect()->back()->withInput()->with('error', validation_list_errors());
        $data['slug'] = $this->uniquePracticeSlug($data['slug']);
        $data['builder_json'] = $this->normaliseBuilder($data['builder_json']);
        $this->practiceAreas->insert($data);
        return redirect()->to(site_url('admin/cms/practice-areas'))->with('success', 'Practice area created.');
    }

    public function practiceEdit(int $id)
    {
        $item = $this->practiceAreas->find($id);
        if (! $item) return redirect()->to(site_url('admin/cms/practice-areas'))->with('error', 'Practice area not found.');
        return view('cms/practice_builder', ['item' => $item, 'templates' => $this->templates->orderBy('name')->findAll()]);
    }

    public function practiceSave(int $id)
    {
        if (! $this->practiceAreas->find($id)) return redirect()->to(site_url('admin/cms/practice-areas'))->with('error', 'Practice area not found.');
        $data = $this->practiceData();
        if (! $this->validatePractice($data)) return redirect()->back()->withInput()->with('error', validation_list_errors());
        $data['slug'] = $this->uniquePracticeSlug($data['slug'], $id);
        $data['builder_json'] = $this->normaliseBuilder($data['builder_json']);
        $this->practiceAreas->update($id, $data);
        return redirect()->to(site_url('admin/cms/practice-areas'))->with('success', 'Practice area and layout saved.');
    }

    public function practiceBuilder(int $id)
    {
        return $this->practiceEdit($id);
    }

    public function templateStore()
    {
        $name = trim((string) $this->request->getPost('name'));
        $type = trim((string) $this->request->getPost('section_type'));
        $data = (string) $this->request->getPost('data');
        if ($name === '' || $type === '' || ! $this->validJson($data)) return $this->jsonResponse(['ok'=>false,'message'=>'Invalid template data.'], 422);
        $id = $this->templates->insert(['name'=>$name,'section_type'=>$type,'data'=>$data], true);
        return $this->jsonResponse(['ok'=>true,'id'=>$id]);
    }

    public function templateDelete(int $id)
    {
        $this->templates->delete($id);
        return $this->jsonResponse(['ok'=>true]);
    }


    private function blankPracticeBuilderItem(): array
    {
        return ['id'=>0,'name'=>'','slug'=>'','short_description'=>'','content'=>'','builder_json'=>'','image'=>'','is_active'=>1,'sort_order'=>0];
    }

    private function blankUpdateBuilderItem(): array
    {
        return ['id'=>0,'title'=>'','slug'=>'','excerpt'=>'','content'=>'','builder_json'=>'','category'=>'Article','status'=>'draft','published_at'=>null,'created_at'=>date('Y-m-d H:i:s')];
    }

    private function builderToLegacyContent(string $json): string
    {
        $sections=json_decode($json,true); if(!is_array($sections)) return '';
        $html=''; foreach($sections as $s){ if(!is_array($s)||!empty($s['hidden'])) continue; $d=is_array($s['data']??null)?$s['data']:[]; $content=(string)($d['content']??$d['text']??''); if($content!=='') $html.='<section>'.$content.'</section>'; }
        return $html;
    }

    private function updateData(): array
    {
        return ['title'=>trim((string)$this->request->getPost('title')),'slug'=>url_title(trim((string)$this->request->getPost('slug')),'-',true),'excerpt'=>trim((string)$this->request->getPost('excerpt')),'content'=>(string)$this->request->getPost('content'),'category'=>trim((string)$this->request->getPost('category')) ?: 'Article','status'=>$this->request->getPost('status') === 'published' ? 'published' : 'draft','published_at'=>$this->normaliseDate($this->request->getPost('published_at')),'builder_json'=>(string)$this->request->getPost('builder_json')];
    }

    private function practiceData(): array
    {
        $builder = (string)$this->request->getPost('builder_json');
        return ['name'=>trim((string)$this->request->getPost('name')),'slug'=>url_title(trim((string)$this->request->getPost('slug')),'-',true),'short_description'=>trim((string)$this->request->getPost('short_description')),'content'=>(string)$this->request->getPost('content'),'builder_json'=>$builder,'image'=>trim((string)$this->request->getPost('image')),'is_active'=>$this->request->getPost('is_active') ? 1 : 0,'sort_order'=>max(0,(int)$this->request->getPost('sort_order'))];
    }

    private function validateUpdate(array $data): bool
    {
        return $this->validateData($data, ['title'=>'required|max_length[180]','slug'=>'required|max_length[190]','content'=>'permit_empty','category'=>'required|max_length[50]']);
    }

    private function validatePractice(array $data): bool
    {
        return $this->validateData($data, ['name'=>'required|max_length[160]','slug'=>'required|max_length[190]','image'=>'permit_empty|max_length[255]']);
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug ?: 'legal-update'; $candidate = $base; $n=2;
        while (true) { $builder=$this->updates->where('slug',$candidate); if($ignoreId) $builder->where('id !=',$ignoreId); if(!$builder->first()) return $candidate; $candidate=$base.'-'.$n++; }
    }

    private function uniquePracticeSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug ?: 'practice-area'; $candidate=$base; $n=2;
        while (true) { $builder=$this->practiceAreas->where('slug',$candidate); if($ignoreId) $builder->where('id !=',$ignoreId); if(!$builder->first()) return $candidate; $candidate=$base.'-'.$n++; }
    }

    private function normaliseDate(?string $value): ?string
    {
        if (!$value) return null; $timestamp=strtotime($value); return $timestamp ? date('Y-m-d H:i:s',$timestamp) : null;
    }

    private function normaliseBuilder(string $json): string
    {
        $decoded=json_decode($json,true);
        if (!is_array($decoded)) $decoded=[];
        $clean=[];
        foreach ($decoded as $section) {
            if (!is_array($section) || empty($section['type'])) continue;
            $section['id'] = (string)($section['id'] ?? uniqid('sec_', true));
            $section['type'] = preg_replace('/[^a-z0-9_-]/i','',(string)$section['type']);
            $section['data'] = is_array($section['data'] ?? null) ? $section['data'] : [];
            $section['hidden'] = !empty($section['hidden']);
            $clean[]=$section;
        }
        return json_encode(array_values($clean), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }

    private function validJson(string $value): bool { json_decode($value, true); return json_last_error() === JSON_ERROR_NONE; }
    private function jsonResponse(array $data, int $status=200) { return $this->response->setStatusCode($status)->setJSON($data); }
}
