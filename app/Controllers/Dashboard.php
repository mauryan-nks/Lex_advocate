<?php
namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\InvoicePaymentModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $userId = (int) session()->get('user_id');
        $invoiceModel = new InvoiceModel();
        $paymentModel = new InvoicePaymentModel();

        $invoices = $invoiceModel->where('user_id', $userId)->orderBy('id', 'DESC')->findAll();
        $open = array_filter($invoices, static fn($row) => in_array($row['payment_status'] ?? '', ['UNPAID', 'PARTIAL'], true));
        $total = array_sum(array_map(static fn($row) => (float) ($row['total'] ?? 0), $invoices));
        $paid = 0.0;
        foreach ($invoices as $invoice) {
            $sum = $paymentModel->selectSum('amount')->where('invoice_id', (int) $invoice['id'])->first();
            $paid += (float) ($sum['amount'] ?? 0);
        }

        return view('user/dashboard', [
            'title' => 'Client Dashboard',
            'invoices' => array_slice($invoices, 0, 5),
            'invoiceCount' => count($invoices),
            'openInvoiceCount' => count($open),
            'totalBilled' => $total,
            'totalPaid' => $paid,
            'outstanding' => max(0, $total - $paid),
        ]);
    }
}
