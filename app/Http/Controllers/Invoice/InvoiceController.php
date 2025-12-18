<?php

namespace App\Http\Controllers\Invoice;

use Inertia\Inertia;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use App\Models\SewingReceive;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CustomerPayment;
use App\Services\RecalculationPayments\RecalculateCustomerPaymentService;

class InvoiceController extends Controller
{
    //sale page
    public function salePage()
    {
        $sewingReceive = SewingReceive::with('sewing.cuttingReceive.cutting.category')->get();
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
                'total' => $request->total_amount,
                'invoice_date' => $request->invoice_date,
                'challan_no' => $request->challan_no,
                'challan_type'=>'product',
                'particulars' => 'Product Sale'
            ]);


            Customer::find($request->customer_id)->increment('due_amount', $request->total_amount);


            foreach ($request->products as $product) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'sewing_receive_id' => $product['id'],
                    'unit' => $product['weight'],
                    'sale_price' => $product['sale_price']
                ]);

                //update available unit
                SewingReceive::find($product['id'])->decrement('available_unit', $product['weight']);

            }
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Invoice Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'something went wrong', 'error' => '']);
        }
    }

    //delete invoice
    public function deleteInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $invoiceProducts = InvoiceProduct::where('invoice_id', $request->invoice_id)->get();
            foreach ($invoiceProducts as $product) {
                SewingReceive::find($product->sewing_receive_id)->increment('available_unit', $product->unit);
                $product->delete();
            }
            $invoice = Invoice::find($request->invoice_id);
            CustomerPayment::where('challan_no', $invoice->challan_no)->where('challan_type','product')->delete();
            RecalculateCustomerPaymentService::recalculateCustomerPayment($invoice->customer_id);
            $invoice->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Invoice Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Invoice Not Deleted', 'error' => '']);
        }
    }
}
