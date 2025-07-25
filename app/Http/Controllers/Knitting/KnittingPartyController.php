<?php

namespace App\Http\Controllers\Knitting;

use Exception;
use Inertia\Inertia;
use App\Models\Knitting;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\KnittingPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KnittingPartyController extends Controller
{
    //knitting party list
    public function knittingPartyList()
    {
        $knittingPartyList = KnittingParty::with('knittings')->get();
        return Inertia::render('Knittings/KnittingParty/KnittingPartyListPage', ['knittingPartyList' => $knittingPartyList]);
    }

    //knitting party detail list
    public function knittingPartyDetailList(Request $request)
    {
        $knittingPayments = KnittingPayment::latest()->first();
        $knittings = Knitting::where('knitting_party_id', $request->knitting_party_id)->with('knittingParty')->get();
        return Inertia::render('Knittings/KnittingParty/KnittingPartyDetailListPage', ['knittings' => $knittings, 'knittingPayments' => $knittingPayments]);
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

    //knitting payment list
    public function knittingPaymentList(Request $request)
    {
        $knittingPayment = KnittingPayment::where('knitting_party_id', $request->knitting_party_id)->with('knittingParty')->get();
        $totalPayment = KnittingPayment::where('knitting_party_id', $request->knitting_party_id)->sum('amount');
        return Inertia::render('Knittings/KnittingParty/KnittingPaymentListPage', ['knittingPayment' => $knittingPayment, 'totalPayment' => $totalPayment]);
    }

    //save knitting payment
    public function saveKnittingPayment(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['message' => 'Please Enter valid amount']);
        }
        DB::beginTransaction();
        try {
            $knittingParty = KnittingParty::findOrFail($request->knitting_party_id);
            $knittingParty->decrement('due_amount', $request->amount);
            KnittingPayment::create([
                'knitting_party_id' => $request->knitting_party_id,
                'amount' => $request->amount
            ]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Payment Saved Successfully', 'error' => '']);
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
