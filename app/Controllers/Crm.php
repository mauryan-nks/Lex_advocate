<?php
namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\CaseModel;
use CodeIgniter\Controller;

class Crm extends Controller
{
    public function index()
    {
        $clients = new ClientModel();
        $cases = new CaseModel();
        return view('admin/crm/index', [
            'clientCount' => $clients->countAllResults(),
            'activeClientCount' => $clients->where('status', 'active')->countAllResults(),
            'caseCount' => $cases->countAllResults(),
            'openCaseCount' => $cases->whereIn('status', ['open', 'active'])->countAllResults(),
            'recentClients' => (new ClientModel())->orderBy('id', 'DESC')->findAll(8),
            'recentCases' => (new CaseModel())->orderBy('id', 'DESC')->findAll(8),
        ]);
    }

    public function clients()
    {
        return view('admin/crm/clients/index', ['clients' => (new ClientModel())->orderBy('id', 'DESC')->findAll()]);
    }

    public function createClient()
    {
        return view('admin/crm/clients/form', ['client' => null, 'action' => site_url('admin/crm/clients/create')]);
    }

    public function storeClient()
    {
        $data = $this->clientData();
        if (!$this->validate(['name' => 'required|max_length[160]', 'email' => 'permit_empty|valid_email|max_length[190]', 'phone' => 'permit_empty|max_length[30]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        (new ClientModel())->insert($data);
        return redirect()->to(site_url('admin/crm/clients'))->with('message', 'Client created successfully.');
    }

    public function editClient(int $id)
    {
        $client = (new ClientModel())->find($id);
        if (!$client) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('admin/crm/clients/form', ['client' => $client, 'action' => site_url('admin/crm/clients/'.$id.'/edit')]);
    }

    public function updateClient(int $id)
    {
        $client = (new ClientModel())->find($id);
        if (!$client) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (!$this->validate(['name' => 'required|max_length[160]', 'email' => 'permit_empty|valid_email|max_length[190]', 'phone' => 'permit_empty|max_length[30]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        (new ClientModel())->update($id, $this->clientData());
        return redirect()->to(site_url('admin/crm/clients'))->with('message', 'Client updated successfully.');
    }

    public function deleteClient(int $id)
    {
        (new ClientModel())->delete($id);
        return redirect()->to(site_url('admin/crm/clients'))->with('message', 'Client deleted.');
    }

    public function cases()
    {
        $caseModel = new CaseModel();
        $cases = $caseModel->select('cases.*, clients.name as client_name')
            ->join('clients', 'clients.id = cases.client_id', 'left')
            ->orderBy('cases.id', 'DESC')->findAll();
        return view('admin/crm/cases/index', ['cases' => $cases]);
    }

    public function createCase()
    {
        return view('admin/crm/cases/form', [
            'case' => null,
            'clients' => (new ClientModel())->where('status', 'active')->orderBy('name')->findAll(),
            'action' => site_url('admin/crm/cases/create'),
        ]);
    }

    public function storeCase()
    {
        if (!$this->validate(['client_id' => 'required|is_natural_no_zero', 'title' => 'required|max_length[200]', 'case_number' => 'permit_empty|max_length[100]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        (new CaseModel())->insert($this->caseData());
        return redirect()->to(site_url('admin/crm/cases'))->with('message', 'Case created successfully.');
    }

    public function editCase(int $id)
    {
        $case = (new CaseModel())->find($id);
        if (!$case) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('admin/crm/cases/form', [
            'case' => $case,
            'clients' => (new ClientModel())->where('status', 'active')->orderBy('name')->findAll(),
            'action' => site_url('admin/crm/cases/'.$id.'/edit'),
        ]);
    }

    public function updateCase(int $id)
    {
        if (!(new CaseModel())->find($id)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (!$this->validate(['client_id' => 'required|is_natural_no_zero', 'title' => 'required|max_length[200]', 'case_number' => 'permit_empty|max_length[100]'])) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        (new CaseModel())->update($id, $this->caseData());
        return redirect()->to(site_url('admin/crm/cases'))->with('message', 'Case updated successfully.');
    }

    public function deleteCase(int $id)
    {
        (new CaseModel())->delete($id);
        return redirect()->to(site_url('admin/crm/cases'))->with('message', 'Case deleted.');
    }

    private function clientData(): array
    {
        return [
            'name' => trim((string)$this->request->getPost('name')),
            'email' => trim((string)$this->request->getPost('email')) ?: null,
            'phone' => trim((string)$this->request->getPost('phone')) ?: null,
            'alternate_phone' => trim((string)$this->request->getPost('alternate_phone')) ?: null,
            'address' => trim((string)$this->request->getPost('address')) ?: null,
            'city' => trim((string)$this->request->getPost('city')) ?: null,
            'state' => trim((string)$this->request->getPost('state')) ?: null,
            'notes' => trim((string)$this->request->getPost('notes')) ?: null,
            'status' => $this->request->getPost('status') === 'inactive' ? 'inactive' : 'active',
        ];
    }

    private function caseData(): array
    {
        return [
            'client_id' => (int)$this->request->getPost('client_id'),
            'case_number' => trim((string)$this->request->getPost('case_number')) ?: null,
            'title' => trim((string)$this->request->getPost('title')),
            'court' => trim((string)$this->request->getPost('court')) ?: null,
            'case_type' => trim((string)$this->request->getPost('case_type')) ?: null,
            'filing_date' => $this->request->getPost('filing_date') ?: null,
            'status' => trim((string)$this->request->getPost('status')) ?: 'open',
            'description' => trim((string)$this->request->getPost('description')) ?: null,
        ];
    }
}
