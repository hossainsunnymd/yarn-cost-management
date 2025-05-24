<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Knitting;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\YarnPurchase;

class KnittingController extends Controller
{

    //knitting party list
    public function knittingPartyList() {
        $knittingPartyList = KnittingParty::all();
        return Inertia::render('Knittings/KnittingPartyListPage',['knittingPartyList' => $knittingPartyList]);
    }

    //knitting party save page
    public function knittingPartySavePage(Request $request) {
        $knittingParty=KnittingParty::find($request->knitting_party_id);
        return Inertia::render('Knittings/KnittingPartySavePage',['knittingParty' => $knittingParty]);
    }

    //create knitting party
    public function createKnittingParty(Request $request) {

        $data=[
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'total_amount'=>0,
            'due_amount'=>0
        ];

        KnittingParty::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Party Created Successfully','error' => '']);
    }

    //update knitting party
    public function updateKnittingParty(Request $request) {

        $data=[
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ];

        KnittingParty::where('id',$request->knitting_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Party Updated Successfully','error' => '']);
    }

    //knitting list
    public function knittingList() {
        $knittingList = Knitting::with('knittingParty','yarnPurchase')->get();
        return Inertia::render('Knittings/KnittingListPage',['knittingList' => $knittingList]);
    }

    //knitting save page
    public function knittingSavePage(Request $request) {
        $knittingPartyList = KnittingParty::all();
        return Inertia::render('Knittings/KnittingSavePage',['knittingPartyList' => $knittingPartyList]);
    }

    //create knitting
    public function createKnitting(Request $request) {

        $data=[
            'knitting_party_id'=>$request->knitting_party_id,
            'yarn_purchase_id'=>$request->yarn_purchase_id,
            'unit'=>$request->unit,
            'total_amount'=>$request->total_amount,
        ];
        YarnPurchase::where('id',$request->yarn_purchase_id)->decrement('unit', $request->unit);

        Knitting::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Created Successfully','error' => '']);
    }
}
