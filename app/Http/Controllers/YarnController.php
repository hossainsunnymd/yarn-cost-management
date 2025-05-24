<?php

namespace App\Http\Controllers;

use App\Models\YarnParty;
use App\Models\YarnPurchase;
use Inertia\Inertia;
use Illuminate\Http\Request;

class YarnController extends Controller
{
    //yarn party list
    public function yarnPartyList() {
        $yarnPartyList = YarnParty::all();
        return Inertia::render('Yarn/YarnPartyListPage',['yarnPartyList' => $yarnPartyList]);
    }

    //yarn save page
    public function yarnPartySavePage(Request $request) {

        $yarnParty=YarnParty::find($request->yarn_party_id);
        return Inertia::render('Yarn/YarnPartySavePage',['yarnParty' => $yarnParty]);
    }

    //create yarn party
    public function createYarnParty(Request $request) {

        $data=[
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'total_amount'=>0,
            'due_amount'=>0

        ];

        YarnParty::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Created Successfully','error' => '']);
    }

    //update yarn party
    public function updateYarnParty(Request $request) {
         $data=[
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'total_amount'=>0,
            'due_amount'=>0
        ];
        YarnParty::find($request->yarn_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Updated Successfully','error' => '']);
    }

    //delete yarn party
    public function yarnPartyDelete(Request $request) {
        YarnParty::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Deleted Successfully','error' => '']);
    }

    //yarn purchase list
    public function yarnPurchaseList() {
        $yarnPurchaseList = YarnPurchase::all();
        return Inertia::render('Yarn/YarnPurchaseListPage',['yarnPurchaseList' => $yarnPurchaseList]);
    }

    //yarn save page
    public function yarnPurchaseSavePage(Request $request) {
        $yarnParty=YarnParty::all();
        $yarnPurchase=YarnPurchase::find($request->id);
        return Inertia::render('Yarn/YarnPurchaseSavePage',['yarnPurchase' => $yarnPurchase,'yarnParty'=>$yarnParty]);
    }

    //create yarn purchase
    public function createYarnPurchase(Request $request) {

        $data=[
            'yarn_party_id'=>$request->yarn_party_id,
            'lot_no'=>$request->lot_no,
            'unit'=>$request->unit,
            'name'=>$request->name,
            'description'=>$request->description,
            'weight'=>$request->weight,
            'bags'=>$request->bags,
            'yarn_rate'=>$request->yarn_rate,
            'bill_amount'=>$request->bill_amount,
            'labour_cost'=>$request->labour_cost,
            'total_amount'=>$request->total_amount
        ];

        YarnPurchase::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Purchase Created Successfully','error' => '']);
    }

    //update yarn purchase
    public function updateYarnPurchase(Request $request) {
         $data=[
            'yarn_party_id'=>$request->yarn_party_id,
            'lot_no'=>$request->lot_no,
            'unit'=>$request->unit,
            'name'=>$request->name,
            'description'=>$request->description,
            'weight'=>$request->weight,
            'bags'=>$request->bags,
            'yarn_rate'=>$request->yarn_rate,
            'bill_amount'=>$request->bill_amount,
            'labour_cost'=>$request->labour_cost,
            'total_amount'=>$request->total_amount
        ];
        YarnPurchase::find($request->id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Purchase Updated Successfully','error' => '']);
    }

    //delete yarn purchase
    public function yarnPurchaseDelete(Request $request) {
        YarnPurchase::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Purchase Deleted Successfully','error' => '']);
    }

}
