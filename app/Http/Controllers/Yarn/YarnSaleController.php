<?php

namespace App\Http\Controllers\Yarn;

use Exception;
use Inertia\Inertia;
use App\Models\YarnSale;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class YarnSaleController extends Controller
{
    //yarn sale page
    public function yarnSalePage()
    {
        $yarnSale = YarnSale::all();
        return Inertia::render('Yarn/YarnSale/YarnSalePage', ['yarnSale' => $yarnSale]);
    }

    //create yarn sale
    public function createYarnSale(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'unit' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        }

        //check is yarn unit available
        $yarnPurchase = YarnPurchase::find($request->yarn_purchase_id);
        if ($yarnPurchase->available_unit < $request->unit) {
            return redirect()->back()->with(['status' => false, 'message' => 'Yarn unit not available', 'error' => '']);
        }
        DB::beginTransaction();
        try {
            $data = [
                'yarn_purchase_id' => $request->yarn_purchase_id,
                'unit' => $request->unit,
                'total_amount' => $request->total_amount,

            ];
            YarnSale::create($data);
            $yarnPurchase = YarnPurchase::find($request->yarn_purchase_id);
            $yarnPurchase->decrement('available_unit', $request->unit);
            $perUnitCost = $yarnPurchase->per_unit_cost;
            $currentTotalAmount = $yarnPurchase->available_unit * $perUnitCost;
            $yarnPurchase->update(['current_total_amount' => $currentTotalAmount]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Yarn Sale Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'something went wrong', 'error' => '']);
        }
    }

    //yarn sale list
    public function yarnSaleList()
    {
        $yarnSaleList = YarnSale::all();
        return Inertia::render('Yarn/YarnSale/YarnSaleListPage', ['yarnSaleList' => $yarnSaleList]);
    }
}
