<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\YarnParty;
use App\Models\YarnPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YarnPartyController extends Controller
{
      //yarn party list
    public function yarnPartyList()
    {
        $yarnPartyList = YarnParty::with('yarnPurchase')->get();
        $yarnPayments=YarnPayment::latest()->first();
        return Inertia::render('Yarn/YarnParty/YarnPartyListPage', ['yarnPartyList' => $yarnPartyList,'yarnPayments'=>$yarnPayments]);
    }

    //yarn save page
    public function yarnPartySavePage(Request $request)
    {

        $yarnParty = YarnParty::find($request->yarn_party_id);
        return Inertia::render('Yarn/YarnParty/YarnPartySavePage', ['yarnParty' => $yarnParty]);
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
            return redirect()->back()->with(['error' => $validation->errors()]);
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

    //delete yarn party
    public function yarnPartyDelete(Request $request)
    {
        YarnParty::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Deleted Successfully', 'error' => '']);
    }
}
