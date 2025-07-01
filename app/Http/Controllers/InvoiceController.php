<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use App\Models\SewingReceive;
use Exception;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //sale page
    public function salePage()
    {
        $sewingReceive = SewingReceive::all();
        $customer = Customer::all();
        return Inertia::render('Sale/SalePage', ['sewingReceive' => $sewingReceive, 'customer' => $customer]);
    }

    //invoice list

    public function invoiceList()
    {
        $invoices = Invoice::with('invoiceProducts.sewingReceive', 'customer')->get();
        return Inertia::render('Invoice/InvoiceListPage', ['invoices' => $invoices]);
    }

    //create invoice
    public function createInvoice(Request $request)
    {

        DB::beginTransaction();
        try {

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'total' => $request->total_weight,
            ]);

            foreach ($request->products as $product) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'sewing_receive_id' => $product['id'],
                    'unit' => $product['weight'],
                ]);
            }
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Invoice Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
}
