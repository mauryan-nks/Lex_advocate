<?php
namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\CaseModel;
use App\Models\UserModel;
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
        $clients = (new ClientModel())
            ->select('clients.*, users.id AS portal_user_id, users.status AS portal_status')
            ->join('users', 'users.client_id = clients.id AND users.role = \'user\'', 'left')
            ->orderBy('clients.id', 'DESC')->findAll();
        return view('admin/crm/clients/index', ['clients' => $clients]);
    }

    public function createClient()
    {
        return view('admin/crm/clients/form', ['client' => null, 'action' => site_url('admin/crm/clients/create')]);
    }

    public function storeClient()
    {
        $rules = [
            'name' => 'required|max_length[160]',
            'email' => 'required|valid_email|max_length[190]',
            'phone' => 'permit_empty|max_length[30]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $email = strtolower(trim((string)$this->request->getPost('email')));
        $users = new UserModel();
        if ($users->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'A portal account already exists for this email address. Use another email.');
        }

        $db = db_connect();
        $db->transStart();
        $clientModel = new ClientModel();
        $clientId = $clientModel->insert($this->clientData(), true);
        $password = $this->generatePortalPassword();
        $userId = $users->insert([
            'name' => trim((string)$this->request->getPost('name')),
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'status' => 'active',
            'client_id' => $clientId,
        ], true);
        $db->transComplete();

        if (!$db->transStatus() || !$clientId || !$userId) {
            return redirect()->back()->withInput()->with('error', 'Client and portal account could not be created.');
        }

        $mailSent = $this->sendPortalCredentials($email, (string)$this->request->getPost('name'), $password);
        return redirect()->to(site_url('admin/crm/clients'))->with('client_credentials', [
            'name' => trim((string)$this->request->getPost('name')),
            'email' => $email,
            'password' => $password,
            'mail_sent' => $mailSent,
        ])->with('message', 'Client and user portal account created successfully.');
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
        $newEmail = strtolower(trim((string)$this->request->getPost('email')));
        $users = new UserModel();
        $linkedUser = $users->where('client_id', $id)->first();
        if ($linkedUser && $newEmail !== '' && $newEmail !== strtolower((string)$linkedUser['email'])) {
            $duplicate = $users->where('email', $newEmail)->where('id !=', $linkedUser['id'])->first();
            if ($duplicate) return redirect()->back()->withInput()->with('error', 'That email address is already used by another portal account.');
        }
        (new ClientModel())->update($id, $this->clientData());
        if ($linkedUser) {
            $users->update($linkedUser['id'], [
                'name' => trim((string)$this->request->getPost('name')),
                'email' => $newEmail !== '' ? $newEmail : $linkedUser['email'],
                'status' => $this->request->getPost('status') === 'inactive' ? 'inactive' : 'active',
            ]);
        }
        return redirect()->to(site_url('admin/crm/clients'))->with('message', 'Client and linked portal account updated successfully.');
    }

    public function deleteClient(int $id)
    {
        (new UserModel())->where('client_id', $id)->delete();
        (new ClientModel())->delete($id);
        return redirect()->to(site_url('admin/crm/clients'))->with('message', 'Client and linked portal account deleted.');
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

    private function generatePortalPassword(): string
    {
        return 'Lx@' . strtoupper(bin2hex(random_bytes(3))) . random_int(10, 99);
    }

    private function sendPortalCredentials(string $emailAddress, string $name, string $password): bool
    {
        $host = trim((string)env('email.SMTPHost', ''));
        if ($host === '') return false;

        $email = service('email');
        $email->initialize([
            'protocol' => env('email.protocol', 'smtp'),
            'SMTPHost' => $host,
            'SMTPUser' => env('email.SMTPUser', ''),
            'SMTPPass' => env('email.SMTPPass', ''),
            'SMTPPort' => (int)env('email.SMTPPort', 587),
            'SMTPTimeout' => (int)env('email.SMTPTimeout', 10),
            'SMTPCrypto' => env('email.SMTPCrypto', 'tls'),
            'mailType' => 'html',
            'charset' => 'UTF-8',
            'wordWrap' => true,
            'newline' => "\r\n",
            'CRLF' => "\r\n",
        ]);
        $from = trim((string)env('email.fromEmail', ''));
        $fromName = trim((string)env('email.fromName', 'Lex Factum & Partners'));
        if ($from === '') return false;

        $email->setFrom($from, $fromName);
        $email->setTo($emailAddress);
        $email->setSubject('Your Lex Factum Client Portal Login');
        $email->setMessage(view('emails/client_portal_credentials', [
            'name' => $name,
            'email' => $emailAddress,
            'password' => $password,
            'loginUrl' => site_url('login'),
        ]));
        return (bool)$email->send(false);
    }
}
