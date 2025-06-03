<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Knitting;
use App\Models\KnittingSale;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class KnittingController extends Controller
{

    //knitting party list
    public function knittingPartyList()
    {
        $knittingPartyList = KnittingParty::with('knittings')->get();
        return Inertia::render('Knittings/KnittingPartyListPage', ['knittingPartyList' => $knittingPartyList]);
    }

    //knitting party save page
    public function knittingPartySavePage(Request $request)
    {
        $knittingParty = KnittingParty::find($request->knitting_party_id);
        return Inertia::render('Knittings/KnittingPartySavePage', ['knittingParty' => $knittingParty]);
    }

    //create knitting party
    public function createKnittingParty(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->with(['errors' => $validator->errors()]);

        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0,
            'last_payment' => 0
        ];

        KnittingParty::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Party Created Successfully', 'error' => '']);
    }

    //update knitting party
    public function updateKnittingParty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->with(['errors' => $validator->errors()]);

        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        KnittingParty::where('id', $request->knitting_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Party Updated Successfully', 'error' => '']);
    }

    //knitting list
    public function knittingList()
    {
        $knittingList = Knitting::with('knittingParty', 'yarnPurchase')->get();
        return Inertia::render('Knittings/KnittingListPage', ['knittingList' => $knittingList]);
    }

    //knitting save page
    public function knittingSavePage(Request $request)
    {
        $knittingPartyList = KnittingParty::all();
        return Inertia::render('Knittings/KnittingSavePage', ['knittingPartyList' => $knittingPartyList]);
    }

    //create knitting
    public function createKnitting(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'knitting_party_id' => 'required',
                'unit' => 'required|numeric',
            ]);

            if($validator->fails()){
                return redirect()->back()->with(['errors' => $validator->errors()]);

            }

        $data = [
            'knitting_party_id' => $request->knitting_party_id,
            'yarn_purchase_id' => $request->yarn_purchase_id,
            'unit' => $request->unit,
            'available_unit' => $request->unit,
        ];

        $yarnPurchase = YarnPurchase::find($request->yarn_purchase_id);
        $yarnPurchase->decrement('weight', $request->unit);
        $perUnitCost = $yarnPurchase->per_unit_cost;
        $currentTotalAmount = $yarnPurchase->weight * $perUnitCost;
        $yarnPurchase->update(['current_total_amount' => $currentTotalAmount]);
        Knitting::create($data);

        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Created Successfully', 'error' => '']);
    }

    //knitting receive list
    public function knittingReceiveList()
    {
        $knittingReceiveList = KnittingReceive::get();
        return Inertia::render('Knittings/KnittingReceiveListPage', ['knittingReceiveList' => $knittingReceiveList]);
    }

    //knitting receive page
    public function knittingReceivePage(Request $request)
    {

        return Inertia::render('Knittings/KnittingReceivePage');
    }

    //create knitting receive
    public function createKnittingReceive(Request $request)
    {

        $knitting = Knitting::find($request->knitting_id);
        $knittingPartyId = $knitting->knitting_party_id;
        $yarnPurchaseId = $knitting->yarn_purchase_id;
        $knittingUnit = $knitting->unit;

        //calculate per unit Yarn cost
        $totalYarnCost = YarnPurchase::find($yarnPurchaseId)->total_amount;
        $totalYarnUnit = YarnPurchase::find($yarnPurchaseId)->unit;
        $perUnitYarnCost = $totalYarnCost / $totalYarnUnit;

        //calculate per unit knitting cost
        $knittingUnitCost = $perUnitYarnCost * $knittingUnit;
        $perUnitKnittingCost = $knittingUnitCost / $knittingUnit;

        //calculate received knitting unit cost
        $receivedKnittingUnitCost = ($request->unit * $perUnitKnittingCost) + $request->knitting_cost;
        $receivePerUnitCost = $receivedKnittingUnitCost / $request->unit;

        if ($request->wastage > 0) {

            $receivedKnittingUnitCost = (($request->unit + $request->wastage) * $perUnitKnittingCost) + $request->knitting_cost;
            $receivePerUnitCost = $receivedKnittingUnitCost / $request->unit;
        }

        $data = [
            'knitting_id' => $request->knitting_id,
            'total_amount' => $receivedKnittingUnitCost,
            'unit' => $request->unit,
            'available_unit' => $request->unit,
            'knitting_cost' => $request->knitting_cost,
            'per_unit_cost' => $receivePerUnitCost,
            'wastage' => 0

        ];

        KnittingReceive::create($data);
        Knitting::where('id', $request->knitting_id)->decrement('available_unit', $request->unit + $request->wastage ?? 0);
        KnittingParty::find($knittingPartyId)->increment('due_amount', $request->knitting_cost);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Receive Created Successfully', 'error' => '']);
    }

    //knitting sale page
    public function knittingSalePage(Request $request)
    {
        return Inertia::render('Knittings/KnittingSalePage');
    }

    //create knitting sale
    public function createKnittingSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        if($validator->fails()){
            return redirect()->back()->with(['errors' => $validator->errors()]);

        }

        $data = [
            'knitting_receive_id' => $request->knitting_receive_id,
            'unit' => $request->unit,
            'total_amount' => $request->total_amount,
        ];
        KnittingSale::create($data);
        KnittingReceive::where('id', $request->knitting_receive_id)->decrement('available_unit', $request->unit);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Sale Created Successfully', 'error' => '']);
    }

    //knitting sale list
    public function knittingSaleList()
    {
        $knittingSaleList = KnittingSale::get();
        return Inertia::render('Knittings/KnittingSaleListPage', ['knittingSaleList' => $knittingSaleList]);
    }

    public function saveKnittingPayment(Request $request){
        $knittingParty=KnittingParty::find($request->knitting_party_id);
        $knittingParty->increment('total_amount', $request->amount);
        $knittingParty->decrement('due_amount', $request->amount);
        $knittingParty->update([
            'last_payment'=>$request->amount,
            'last_payment_date'=>date('Y-m-d', strtotime($request->payment_date))
         ]);
        return redirect('/knitting-party-list')->with(['status' => true, 'message' => 'Knitting Payment Saved Successfully','error' => '']);
    }
}
