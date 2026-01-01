<?php

namespace App\Http\Controllers\Yarn;

use Exception;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\YarnSale;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use App\Models\CustomerPayment;
use App\Models\YarnSaleProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class YarnSaleController extends Controller
{
    //yarn sale page
    public function yarnSalePage()
    {
        $yarns = YarnPurchase::where('available_unit', '>', 0)->with('yarnParty')->get();
        $customers = Customer::all();
        return Inertia::render('Yarn/YarnSale/YarnSalePage', ['yarns' => $yarns, 'customers' => $customers]);
    }

    //create yarn sale
    public function createYarnSale(Request $request)
    {

        DB::beginTransaction();
        try {
            $yarnSale = YarnSale::create([
                'customer_id' => $request->customer_id,
                'challan_no' => $request->challan_no,
                'total_unit' => $request->total_unit,
                'total_amount' => $request->total_amount,
                'sale_date' => $request->sale_date,
                'total_cost' => $request->total_cost
            ]);

            foreach ($request->yarns as $yarn) {
                YarnSaleProduct::create([
                    'yarn_sale_id' => $yarnSale->id,
                    'yarn_purchase_id' => $yarn['id'],
                    'unit' => $yarn['weight'],
                    'price' => $yarn['price'],
                    'total_amount' => $yarn['sale_price'],
                    'per_unit_cost' => $yarn['per_unit_cost']
                ]);
                YarnPurchase::where('id', $yarn['id'])->decrement('available_unit', $yarn['weight']);
            }

            $customer = Customer::find($request->customer_id);
            $customer->increment('due_amount', $request->total_amount);

            CustomerPayment::create([
                'customer_id' => $request->customer_id,
                'amount' => $customer->due_amount,
                'credit' => $request->total_amount,
                'challan_no' => $request->challan_no,
                'challan_type' => 'yarn',
                'particulars' => 'Yarn Sale',
                'date' => $request->sale_date
            ]);

            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Yarn Sale Created Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
        }

    }

    //yarn sale list
    public function yarnSaleList()
    {
        $yarnSaleList = YarnSale::with('customer', 'yarnSaleProducts')->get();
        return Inertia::render('Yarn/YarnSale/YarnSaleListPage', ['yarnSaleList' => $yarnSaleList]);
    }

    //delete yarn sale
    public function yarnSaleDelete($id)
    {
        DB::beginTransaction();
        try {
            $yarnSaleProducts = YarnSaleProduct::where('yarn_sale_id', $id)->get();
            foreach ($yarnSaleProducts as $yarnSaleProduct) {
                YarnPurchase::where('id', $yarnSaleProduct->yarn_purchase_id)->increment('available_unit', $yarnSaleProduct->unit);
                $yarnSaleProduct->delete();
            }
            $yarnSale = YarnSale::find($id);
            CustomerPayment::where('challan_no', $yarnSale->challan_no)->where('challan_type', 'yarn')->delete();
            $yarnSale->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Yarn Sale Deleted Successfully']);

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
