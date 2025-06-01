<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Drying;
use App\Models\DryingReceive;
use App\Models\Fabric;
use App\Models\Knitting;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;

class FabricController extends Controller
{

    //fabric list
    public function fabricList() {
        $fabrics=DryingReceive::with('drying.knitting.yarnPurchase','drying.dryingParty')->get();
        return Inertia::render('Fabric/FabricListPage',['fabrics' => $fabrics]);
    }
}
