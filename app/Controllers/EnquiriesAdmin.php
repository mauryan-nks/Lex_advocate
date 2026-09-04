<?php
namespace App\Controllers;
use App\Models\EnquiryModel;
use CodeIgniter\Controller;

class EnquiriesAdmin extends Controller
{
    protected EnquiryModel $model;
    public function __construct(){ $this->model=new EnquiryModel(); }
    public function index(){
        $items=$this->model->orderBy('created_at','DESC')->findAll();
        return view('admin/enquiries/index',['items'=>$items,'filter'=>'all']);
    }
    public function contact(){
        $items=$this->model->where('type','contact')->orderBy('created_at','DESC')->findAll();
        return view('admin/enquiries/index',['items'=>$items,'filter'=>'contact']);
    }
    public function appointments(){
        $items=$this->model->where('type','appointment')->orderBy('created_at','DESC')->findAll();
        return view('admin/enquiries/index',['items'=>$items,'filter'=>'appointment']);
    }
    public function show(int $id){
        $item=$this->model->find($id);
        if(!$item) return redirect()->to(site_url('admin/enquiries'))->with('error','Request not found.');
        return view('admin/enquiries/show',['item'=>$item]);
    }
    public function status(int $id){
        $status=(string)$this->request->getPost('status');
        if(!in_array($status,['new','contacted','scheduled','closed','spam'],true)) $status='new';
        $this->model->update($id,['status'=>$status]);
        return redirect()->back()->with('success','Request status updated.');
    }
    public function delete(int $id){
        $this->model->delete($id);
        return redirect()->to(site_url('admin/enquiries'))->with('success','Request deleted.');
    }
}
