<?php
namespace App\Controllers;

use App\Models\EnquiryModel;
use CodeIgniter\Controller;

class Enquiries extends Controller
{
    protected EnquiryModel $model;
    public function __construct(){ $this->model = new EnquiryModel(); }

    public function contactStore()
    {
        return $this->store('contact');
    }

    public function appointmentStore()
    {
        return $this->store('appointment');
    }

    private function store(string $type)
    {
        $data = [
            'full_name'=>trim((string)$this->request->getPost('full_name')),
            'email'=>trim((string)$this->request->getPost('email')),
            'phone'=>trim((string)$this->request->getPost('phone')),
            'practice_area'=>trim((string)$this->request->getPost('practice_area')),
            'message'=>trim((string)$this->request->getPost('message')),
            'type'=>$type,
            'status'=>'new',
            'preferred_date'=>$this->request->getPost('preferred_date') ?: null,
            'preferred_time'=>$this->request->getPost('preferred_time') ?: null,
        ];
        $rules = ['full_name'=>'required|max_length[160]','email'=>'required|valid_email|max_length[190]','phone'=>'permit_empty|max_length[30]','message'=>'permit_empty'];
        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('error', validation_list_errors());
        }
        $this->model->insert($data);
        return redirect()->back()->with('success', ucfirst($type).' request received.');
    }
}
