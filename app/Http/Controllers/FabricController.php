<?php

namespace App\Http\Controllers;


use Exception;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\FabricSale;
use Illuminate\Http\Request;
use App\Models\DyeingReceive;
use App\Models\FabricSaleProduct;
use Illuminate\Support\Facades\DB;

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
        $fabricSaleList = FabricSale::with('fabricSaleProducts', 'customer')->get();
        return Inertia::render('Fabric/FabricSaleListPage', ['fabricSaleList' => $fabricSaleList]);
    }

    //fabric sale page
    public function fabricSalePage()
    {
        $customers = Customer::all();
        $fabrics = DyeingReceive::all();
        return Inertia::render('Fabric/FabricSalePage', ['customers' => $customers, 'fabrics' => $fabrics]);
    }

    //fabric sale
    public function fabricSale(Request $request)
    {

            DB::beginTransaction();
        try {
            $fabricSale = FabricSale::create([
                'customer_id' => $request->customer_id,
                'total' => $request->total,
            ]);

            foreach ($request->fabrics as $fabric) {
                FabricSaleProduct::create([
                    'fabric_sale_id' =>$fabricSale->id,
                    'dyeing_receive_id' => $fabric['id'],
                    'per_unit_cost' => $fabric['per_unit_cost'],
                    'unit' => $fabric['weight'],
                ]);
                DyeingReceive::findOrFail($fabric['id'])->decrement('available_unit', $fabric['weight']);
            }

            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Fabric Sale Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
}
