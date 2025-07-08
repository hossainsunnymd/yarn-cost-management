<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\DyeingParty;
use Illuminate\Http\Request;
use App\Models\DyeingPayment;
use Illuminate\Support\Facades\Validator;

class DyeingPartyController extends Controller
{
    //dyeing party list
    public function dyeingPartyList()
    {
        $dyeingPartyList = DyeingParty::with('dyeings')->get();
        $dyeingPayment = DyeingPayment::latest()->first();
        return Inertia::render('Dyeings/DyeingParty/DyeingPartyListPage', ['dyeingPartyList' => $dyeingPartyList, 'dyeingPayment' => $dyeingPayment]);
    }

    //dyeing save page
    public function dyeingPartySavePage(Request $request)
    {
        $dyeingParty = DyeingParty::find($request->dyeing_party_id);
        return Inertia::render('Dyeings/DyeingParty/DyeingPartySavePage', ['dyeingParty' => $dyeingParty]);
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
            return redirect()->back()->with(['error' => $validator->errors()]);
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0,
            'last_payment' => 0
        ];
        DyeingParty::create($data);
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
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }


        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0
        ];
        DyeingParty::find($request->dyeing_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Updated Successfully', 'error' => '']);
    }

    //save dyeing payment
    public function saveDyeingPayment(Request $request)
    {
        $dyeingParty = DyeingParty::find($request->dyeing_party_id);
        $dyeingParty->decrement('due_amount', $request->amount);
        DyeingPayment::create([
            'dyeing_party_id' => $request->dyeing_party_id,
            'amount' => $request->amount
        ]);
        return redirect('/dyeing-party-list')->with(['status' => true, 'message' => 'Dyeing Payment Saved Successfully', 'error' => '']);
    }

    //delete dyeing party
    public function dyeingPartyDelete(Request $request)
    {
        DyeingParty::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Deleted Successfully', 'error' => '']);
    }
}
