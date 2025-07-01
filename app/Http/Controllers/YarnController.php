<?php

namespace App\Http\Controllers;

use App\Models\YarnParty;
use App\Models\YarnPayment;
use App\Models\YarnPurchase;
use App\Models\YarnSale;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YarnController extends Controller
{
    //yarn party list
    public function yarnPartyList()
    {
        $yarnPartyList = YarnParty::with('yarnPurchase')->get();
        $yarnPayments=YarnPayment::latest()->first();
        return Inertia::render('Yarn/YarnPartyListPage', ['yarnPartyList' => $yarnPartyList,'yarnPayments'=>$yarnPayments]);
    }

    //yarn save page
    public function yarnPartySavePage(Request $request)
    {

        $yarnParty = YarnParty::find($request->yarn_party_id);
        return Inertia::render('Yarn/YarnPartySavePage', ['yarnParty' => $yarnParty]);
    }

    //create yarn party
    public function createYarnParty(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0,
            'last_payment' => 0

        ];

        YarnParty::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Created Successfully', 'error' => '']);
    }

    //update yarn party
    public function updateYarnParty(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0
        ];
        YarnParty::find($request->yarn_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Updated Successfully', 'error' => '']);
    }

    //delete yarn party
    public function yarnPartyDelete(Request $request)
    {
        YarnParty::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Deleted Successfully', 'error' => '']);
    }

    //yarn purchase list
    public function yarnPurchaseList()
    {
        $yarnPurchaseList = YarnPurchase::all();
        return Inertia::render('Yarn/YarnPurchaseListPage', ['yarnPurchaseList' => $yarnPurchaseList]);
    }

    //yarn save page
    public function yarnPurchaseSavePage(Request $request)
    {
        $yarnParty = YarnParty::all();
        $yarnPurchase = YarnPurchase::find($request->id);
        return Inertia::render('Yarn/YarnPurchaseSavePage', ['yarnPurchase' => $yarnPurchase, 'yarnParty' => $yarnParty]);
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

    //yarn sale page
    public function yarnSalePage()
    {
        $yarnSale = YarnSale::all();
        return Inertia::render('Yarn/YarnSalePage', ['yarnSale' => $yarnSale]);
    }

    //create yarn sale
    public function createYarnSale(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'unit' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        }


        $data = [
            'yarn_purchase_id' => $request->yarn_purchase_id,
            'unit' => $request->unit,
            'total_amount' => $request->total_amount,

        ];
        YarnSale::create($data);
        $yarnPurchase = YarnPurchase::find($request->yarn_purchase_id);
        $yarnPurchase->decrement('available_unit', $request->unit);
        $perUnitCost = $yarnPurchase->per_unit_cost;
        $currentTotalAmount = $yarnPurchase->available_unit * $perUnitCost;
        $yarnPurchase->update(['current_total_amount' => $currentTotalAmount]);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Sale Created Successfully', 'error' => '']);
    }

    //yarn sale list
    public function yarnSaleList()
    {
        $yarnSaleList = YarnSale::all();
        return Inertia::render('Yarn/YarnSaleListPage', ['yarnSaleList' => $yarnSaleList]);
    }

    //yarn payment
    public function saveYarnPayment(Request $request)
    {
        $yarnParty = YarnParty::find($request->yarn_party_id);
        $yarnParty->decrement('due_amount', $request->amount);
        YarnPayment::create([
            'yarn_party_id' => $request->yarn_party_id,
            'amount' => $request->amount
        ]);
        return redirect('/yarn-party-list')->with(['status' => true, 'message' => 'Yarn Payment Saved Successfully', 'error' => '']);
    }
}
