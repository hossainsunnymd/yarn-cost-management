<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Drying;
use App\Models\Fabric;
use App\Models\Knitting;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;

class FabricController extends Controller
{
    //create fabric
    public function createFabric(Request $request) {
        $yarnCost=YarnPurchase::find($request->yarn_purchase_id)->total_amount;
        $knittingCost=Knitting::find($request->knitting_id)->total_amount;
        $dyeingCost=Drying::find($request->dyeing_id)->total_amount;

        $total_cost=$yarnCost+$knittingCost+$dyeingCost;
        $data=[
            'drying_id'=>$request->dyeing_id,
            'unit'=>$request->unit,
            'total_cost'=>$total_cost,
        ];

        Fabric::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Fabric Created Successfully','error' => '']);
    }

    //fabric list
    public function fabricList() {
        $fabrics=Fabric::with('drying.knitting.yarnPurchase')->get();
        return Inertia::render('Fabric/FabricListPage',['fabrics' => $fabrics]);
    }
}
