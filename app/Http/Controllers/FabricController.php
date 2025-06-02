<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use App\Models\FabricSale;
use Illuminate\Http\Request;
use App\Models\DryingReceive;

class FabricController extends Controller
{

    //fabric list
    public function fabricList() {
        $fabrics=DryingReceive::with('drying.knitting.yarnPurchase','drying.dryingParty')->get();
        return Inertia::render('Fabric/FabricListPage',['fabrics' => $fabrics]);
    }

    //fabric sale list
    public function fabricSaleList() {
        $fabricSaleList = FabricSale::all();
        return Inertia::render('Fabric/FabricSaleListPage',['fabricSaleList' => $fabricSaleList]);
    }

    //fabric sale page
    public function fabricSalePage() {
        return Inertia::render('Fabric/FabricSalePage');
    }

    //create fabric sale
    public function createFabricSale(Request $request) {

        $data=[
            'drying_receive_id'=>$request->dyeing_receive_id,
            'unit'=>$request->unit,
            'total_amount'=>$request->total_amount,
        ];
        FabricSale::create($data);
        DryingReceive::find($request->dyeing_receive_id)->decrement('unit', $request->unit);
        return redirect()->back()->with(['status' => true, 'message' => 'Fabric Sale Created Successfully','error' => '']);
    }
}
