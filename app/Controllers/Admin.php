<?php
namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\UserModel;
use App\Models\CaseModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    public function index()
    {
        $invoiceModel = new InvoiceModel();
        $userModel = new UserModel();
        $caseModel = new CaseModel();

        $invoiceRows = $invoiceModel->findAll();
        $pendingInvoices = array_filter($invoiceRows, static fn($row) => in_array($row['payment_status'] ?? '', ['UNPAID', 'PARTIAL'], true));

        return view('admin/dashboard', [
            'title' => 'Admin Console',
            'clientCount' => $userModel->where('role', 'user')->countAllResults(),
            'invoiceCount' => count($invoiceRows),
            'activeCaseCount' => $caseModel->whereIn('status', ['open', 'active'])->countAllResults(),
            'pendingInvoiceCount' => count($pendingInvoices),
        ]);
    }
}
