<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Drying;
use App\Models\Knitting;
use App\Models\DryingParty;
use Illuminate\Http\Request;
use App\Models\DryingReceive;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([ 'errors' => $validator->errors()]);
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0,
            'last_payment'=>0
        ];
        DryingParty::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Created Successfully', 'error' => '']);
    }

    //update dyeing party
    public function updateDyeingParty(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([ 'errors' => $validator->errors()]);
        }


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
        $validator = Validator::make($request->all(), [
            'dyeing_party_id' => 'required',
            'unit' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([ 'errors' => $validator->errors()]);
        }

        $data = [
            'drying_party_id' => $request->dyeing_party_id,
            'knitting_receive_id' => $request->knitting_receive_id,
            'unit' => $request->unit,
            'available_unit' => $request->unit
        ];
        Drying::create($data);
        KnittingReceive::where('id', $request->knitting_receive_id)->decrement('available_unit', $request->unit);

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
        $dyeingPartyId = $dyeing->drying_party_id;
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
            'available_unit' => $request->unit,
            'wastage' => $request->wastage,
        ];

        DryingReceive::create($data);
        Drying::where('id', $request->dyeing_id)->decrement('available_unit', $request->unit+$request->wastage??0);
        DryingParty::find($dyeingPartyId)->increment( 'due_amount', $request->total_amount);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Receive Created Successfully', 'error' => '']);
    }

    //save dyeing payment
    public function saveDyeingPayment(Request $request){
        $dyeingParty=DryingParty::find($request->dyeing_party_id);
        $dyeingParty->increment('total_amount', $request->amount);
        $dyeingParty->decrement('due_amount', $request->amount);
        $dyeingParty->update([
            'last_payment'=>$request->amount,
            'last_payment_date'=>date('Y-m-d', strtotime($request->payment_date))
         ]);
        return redirect('/dyeing-party-list')->with(['status' => true, 'message' => 'Dyeing Payment Saved Successfully','error' => '']);
    }
}
