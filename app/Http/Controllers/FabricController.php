<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use App\Models\FabricSale;
use Illuminate\Http\Request;
use App\Models\DryingReceive;
use App\Models\DyeingReceive;

class FabricController extends Controller
{

    //fabric list
    public function fabricList() {
        $fabrics=DyeingReceive::with('dyeing.knitting.yarnPurchase')->get();
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


}
