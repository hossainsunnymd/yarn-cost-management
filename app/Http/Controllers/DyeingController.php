<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Drying;
use App\Models\DryingParty;
use App\Models\DryingReceive;
use App\Models\Knitting;
use App\Models\KnittingReceive;
use Illuminate\Http\Request;

class DyeingController extends Controller
{
    //dyeing party list
    public function dyeingPartyList()
    {
        $dyeingPartyList = DryingParty::with('dryings')->get();
        return Inertia::render('Dyeing/DyeingPartyListPage', ['dyeingPartyList' => $dyeingPartyList]);
    }

    //dyeing save page
    public function dyeingPartySavePage(Request $request)
    {
        $dyeingParty = DryingParty::find($request->dyeing_party_id);
        return Inertia::render('Dyeing/DyeingPartySavePage', ['dyeingParty' => $dyeingParty]);
    }

    //create dyeing party
    public function createDyeingParty(Request $request)
    {
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0
        ];
        DryingParty::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Created Successfully', 'error' => '']);
    }

    //update dyeing party
    public function updateDyeingParty(Request $request)
    {
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0
        ];
        DryingParty::find($request->dyeing_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Updated Successfully', 'error' => '']);
    }

    //delete dyeing party
    public function dyeingPartyDelete(Request $request)
    {
        DryingParty::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Deleted Successfully', 'error' => '']);
    }

    //dyeing list
    public function dyeingList()
    {

        $dyeingList = Drying::with('knitting.yarnPurchase', 'dryingParty')->get();
        return Inertia::render('Dyeing/DyeingListPage', ['dyeingList' => $dyeingList]);
    }

    //dyeing save page
    public function dyeingSavePage(Request $request)
    {
        $dyeingPartyList = DryingParty::all();
        return Inertia::render('Dyeing/DyeingSavePage', ['dyeingPartyList' => $dyeingPartyList]);
    }

    //create dyeing
    public function createDyeing(Request $request)
    {

        $data = [
            'drying_party_id' => $request->dyeing_party_id,
            'knitting_receive_id' => $request->knitting_receive_id,
            'unit' => $request->unit,
        ];
        Drying::create($data);
        Knitting::where('id', $request->knitting_id)->decrement('unit', $request->unit);

        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Created Successfully', 'error' => '']);
    }

    //dyeing receive page
    public function dyeingReceivePage()
    {
        return Inertia::render('Dyeing/DyeingReceivePage');
    }

    //create dyeing receive
    public function createDyeingReceive(Request $request)
    {
        $dyeing = Drying::find($request->dyeing_id);
        $perUnitknittingReceiveCost = KnittingReceive::find($dyeing->knitting_receive_id)->per_unit_cost;

        $totalDyeingUnitCost = $dyeing->unit * $perUnitknittingReceiveCost;
        $totalCost = $totalDyeingUnitCost + $request->total_amount;
        $perUnitDyeingCost = $totalCost / $dyeing->unit;

        if ($request->wastage > 0) {
            $totalDyeingUnitCost = ($dyeing->unit + $request->wastage) * $perUnitknittingReceiveCost;
            $totalCost = $totalDyeingUnitCost + $request->dyeing_cost;
            $perUnitDyeingCost = $totalCost / $request->unit;
        }

        $data = [
            'drying_id' => $request->dyeing_id,
            'total_amount' => $totalCost,
            'per_unit_cost' => $perUnitDyeingCost,
            'unit' => $request->unit,
            'wastage' => $request->wastage,
        ];

        DryingReceive::create($data);
        Drying::where('id', $request->dyeing_id)->decrement('unit', $request->unit);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Receive Created Successfully', 'error' => '']);
    }
}
