<?php

namespace App\Http\Controllers\Knitting;

use Exception;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\KnittingSale;
use Illuminate\Http\Request;
use App\Models\CustomerPayment;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;
use App\Models\KnittingSaleProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Knitting\KnittingSaleService;


class KnittingSaleController extends Controller
{
    //knitting sale page
    public function knittingSalePage(Request $request)
    {
        $knittingReceives = KnittingReceive::with('knitting')->get();
        $customers = Customer::all();
        return Inertia::render('Knittings/KnittingSale/KnittingSalePage', ['knittingReceives' => $knittingReceives, 'customers' => $customers]);
    }

    //create knitting sale
    public function createKnittingSale(KnittingSaleService $knittingSaleService, Request $request)
    {

        try {
            $knittingSaleService->createKnittingSale($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting sale created successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }

    //knitting sale list
    public function knittingSaleList()
    {
        $knittingSaleList = KnittingSale::with('knittingSaleProducts', 'customer')->get();
        return Inertia::render('Knittings/KnittingSale/KnittingSaleListPage', ['knittingSaleList' => $knittingSaleList]);
    }

    //delete knitting sale
    public function deleteKnittingSale($id)
    {
        DB::beginTransaction();
        try {
            $knittingSaleProducts = KnittingSaleProduct::where('knitting_sale_id', $id)->get();
            foreach ($knittingSaleProducts as $knittingSaleProduct) {
                KnittingReceive::find($knittingSaleProduct->knitting_receive_id)->increment('available_unit', $knittingSaleProduct->unit);
                $knittingSaleProduct->delete();
            }
            $knittingSale = KnittingSale::find($id);
            CustomerPayment::where('challan_no', $knittingSale->challan_no)->where('challan_type', 'knitting')->delete();
            Customer::find($knittingSale->customer_id)->decrement('due_amount', $knittingSale->total_amount);
            $knittingSale->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting sale deleted successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }
}
