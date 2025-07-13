<?php

namespace App\Http\Controllers\Knitting;

use Exception;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\KnittingPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class KnittingPartyController extends Controller
{
    //knitting party list
    public function knittingPartyList()
    {
        $knittingPartyList = KnittingParty::with('knittings')->get();
        $knittingPayment = KnittingPayment::latest()->first();
        return Inertia::render('Knittings/KnittingParty/KnittingPartyListPage', ['knittingPartyList' => $knittingPartyList, 'knittingPayment' => $knittingPayment]);
    }

    //knitting party save page
    public function knittingPartySavePage(Request $request)
    {
        $knittingParty = KnittingParty::find($request->knitting_party_id);
        return Inertia::render('Knittings/KnittingParty/KnittingPartySavePage', ['knittingParty' => $knittingParty]);
    }

    //create knitting party
    public function createKnittingParty(Request $request)
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

        if ($validator->fails()) {
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

    //save knitting payment
    public function saveKnittingPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $knittingParty = KnittingParty::findOrFail($request->knitting_party_id);
            $knittingParty->decrement('due_amount', $request->amount);
            KnittingPayment::create([
                'knitting_party_id' => $request->knitting_party_id,
                'amount' => $request->amount
            ]);
            DB::commit();
            return redirect('/knitting-party-list')->with(['status' => true, 'message' => 'Knitting Payment Saved Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //delete knitting party
    public function knittingPartyDelete(Request $request)
    {
        try {
            KnittingParty::find($request->id)->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Party Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }
}
