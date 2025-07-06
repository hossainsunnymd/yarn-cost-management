<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\YarnParty;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YarnPurchaseController extends Controller
{
     //yarn purchase list
    public function yarnPurchaseList()
    {
        $yarnPurchaseList = YarnPurchase::all();
        return Inertia::render('Yarn/YarnPurchase/YarnPurchaseListPage', ['yarnPurchaseList' => $yarnPurchaseList]);
    }

    //yarn save page
    public function yarnPurchaseSavePage(Request $request)
    {
        $yarnParty = YarnParty::all();
        $yarnPurchase = YarnPurchase::find($request->id);
        return Inertia::render('Yarn/YarnPurchase/YarnPurchaseSavePage', ['yarnPurchase' => $yarnPurchase, 'yarnParty' => $yarnParty]);
    }

    //create yarn purchase
    public function createYarnPurchase(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'yarn_party_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'unit' => 'required|numeric',
            'bags' => 'required|numeric',
            'yarn_rate' => 'required|numeric',
            'labour_cost' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        }

        $bill_amount = $request->unit * $request->yarn_rate;
        $total_amount = $bill_amount + $request->labour_cost;
        $per_unit_cost = $total_amount / $request->unit;
        $data = [
            'yarn_party_id' => $request->yarn_party_id,
            'unit' => $request->unit,
            'available_unit' => $request->unit,
            'per_unit_cost' => $per_unit_cost,
            'name' => $request->name,
            'description' => $request->description,
            'bags' => $request->bags,
            'yarn_rate' => $request->yarn_rate,
            'bill_amount' => $bill_amount,
            'labour_cost' => $request->labour_cost,
            'total_amount' => $total_amount,
            'current_total_amount' => $total_amount
        ];

        YarnPurchase::create($data);
        YarnParty::find($request->yarn_party_id)->increment('due_amount', $bill_amount);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Purchase Created Successfully', 'error' => '']);
    }

    //update yarn purchase
    public function updateYarnPurchase(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'yarn_party_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'unit' => 'required|numeric',
            'bags' => 'required|numeric',
            'yarn_rate' => 'required|numeric',
            'labour_cost' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        }

        $bill_amount = $request->unit * $request->yarn_rate;
        $total_amount = $bill_amount + $request->labour_cost;
        $per_unit_cost = $total_amount / $request->unit;
        $data = [
            'yarn_party_id' => $request->yarn_party_id,
            'unit' => $request->unit,
            'available_unit' => $request->unit,
            'per_unit_cost' => $per_unit_cost,
            'name' => $request->name,
            'description' => $request->description,
            'bags' => $request->bags,
            'yarn_rate' => $request->yarn_rate,
            'bill_amount' => $bill_amount,
            'labour_cost' => $request->labour_cost,
            'total_amount' => $total_amount,
            'current_total_amount' => $total_amount
        ];
        YarnPurchase::find($request->id)->update($data);
        YarnParty::find($request->yarn_party_id)->increment('due_amount', $bill_amount);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Purchase Updated Successfully', 'error' => '']);
    }

    //delete yarn purchase
    public function yarnPurchaseDelete(Request $request)
    {
        YarnPurchase::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Purchase Deleted Successfully', 'error' => '']);
    }

}
