<?php

namespace App\Http\Controllers\Fabric;


use Exception;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\FabricSale;
use Illuminate\Http\Request;
use App\Models\DyeingReceive;
use App\Models\CustomerPayment;
use App\Models\FabricSaleProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FabricController extends Controller
{

    //fabric list
    public function fabricList()
    {
        $fabrics = DyeingReceive::with('dyeing.knitting.yarnPurchase')->get();
        return Inertia::render('Fabric/FabricListPage', ['fabrics' => $fabrics]);
    }

    //fabric sale list
    public function fabricSaleList()
    {
        $fabricSaleList = FabricSale::with('fabricSaleProducts.dyeingReceive.dyeing', 'customer')->get();
        return Inertia::render('Fabric/FabricSaleListPage', ['fabricSaleList' => $fabricSaleList]);
    }

    //fabric sale page
    public function fabricSalePage()
    {
        $customers = Customer::all();
        $fabrics = DyeingReceive::with('dyeing')->get();
        return Inertia::render('Fabric/FabricSalePage', ['customers' => $customers, 'fabrics' => $fabrics]);
    }

    //fabric sale
    public function fabricSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'challan_no' => 'unique:fabric_sales,challan_no',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['status' => false, 'message' => 'Challan no Already Exist', 'error' => '']);
        }

        DB::beginTransaction();
        try {
            $fabricSale = FabricSale::create([
                'challan_no' => $request->challan_no,
                'sale_date' => $request->sale_date,
                'customer_id' => $request->customer_id,
                'total_unit' => $request->total_unit,
                'total_amount' => $request->total_amount,
                'total_cost' => $request->total_cost
            ]);

            foreach ($request->fabrics as $fabric) {
                FabricSaleProduct::create([
                    'fabric_sale_id' => $fabricSale->id,
                    'dyeing_receive_id' => $fabric['id'],
                    'price' => $fabric['price'],
                    'unit' => $fabric['weight'],
                    'roll' => $fabric['roll'],
                    'total_amount' => $fabric['total_amount'],
                    'per_unit_cost' => $fabric['per_unit_cost']
                ]);
                $dyeingReceive = DyeingReceive::findOrFail($fabric['id']);
                $dyeingReceive->decrement('available_unit', $fabric['weight']);
                $dyeingReceive->decrement('roll', $fabric['roll']);
            }


            $customer = Customer::findOrFail($request->customer_id);
            $customer->increment('due_amount', $request->total_amount);
            CustomerPayment::create([
                'challan_no' => $request->challan_no,
                'challan_type'=>'fabric',
                'particulars' => 'Fabric Sale',
                'customer_id' => $request->customer_id,
                'debit' => $request->total_amount,
                'date' => $request->sale_date
            ]);

            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Fabric Sale Created Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    //delete fabric sale
    public function deleteFabricSale($id)
    {
        DB::beginTransaction();
        try {
            $fabricSaleProducts = FabricSaleProduct::where('fabric_sale_id', $id)->get();
            
            foreach ($fabricSaleProducts as $fabricSaleProduct) {
                $dyeingReceive = DyeingReceive::findOrFail($fabricSaleProduct->dyeing_receive_id);
                $dyeingReceive->increment('available_unit', $fabricSaleProduct->unit);
                $dyeingReceive->increment('roll', $fabricSaleProduct->roll);
                $fabricSaleProduct->delete();

            }
            $fabricSale = FabricSale::find($id);
            CustomerPayment::where('challan_no', $fabricSale->challan_no)->where('challan_type','fabric')->delete();
            $fabricSale->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Fabric Sale Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
