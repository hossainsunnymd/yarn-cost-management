<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Knitting;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;


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
        ];
        YarnPurchase::where('id',$request->yarn_purchase_id)->decrement('weight', $request->unit);
        Knitting::create($data);

        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Created Successfully','error' => '']);
    }

    //knitting receive list
    public function knittingReceiveList() {
        $knittingReceiveList = KnittingReceive::get();
        return Inertia::render('Knittings/KnittingReceiveListPage',['knittingReceiveList' => $knittingReceiveList]);
    }

    //knitting receive page
    public function knittingReceivePage(Request $request) {

        return Inertia::render('Knittings/KnittingReceivePage');
    }

    //create knitting receive
    public function createKnittingReceive(Request $request)  {
         $data=[
            'knitting_id'=>$request->knitting_id,
            'total_amount'=>$request->total_amount,
            'unit'=>$request->unit,
         ];

         KnittingReceive::create($data);
         Knitting::where('id',$request->knitting_id)->decrement('unit', $request->unit);
         return redirect()->back()->with(['status' => true, 'message' => 'Knitting Receive Created Successfully','error' => '']);
    }


}
