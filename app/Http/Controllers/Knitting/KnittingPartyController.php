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
            'due_amount' => 0,
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
            'due_amount' => 0
        ];

        KnittingParty::where('id', $request->knitting_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Party Updated Successfully', 'error' => '']);
    }

    //knitting payment list
    public function knittingPaymentList(Request $request)
    {
        $knittingPayments = KnittingPayment::where('knitting_party_id', $request->knitting_party_id)
        ->orderBy('id', 'asc')
        ->paginate(100)->withQueryString();
        $knittingParty = KnittingParty::find($request->knitting_party_id);

        $pagination=[
            'next_page_url' => $knittingPayments->nextPageUrl(),
            'prev_page_url' => $knittingPayments->previousPageUrl(),
            'last_page'=> $knittingPayments->lastPage(),
        ];

        $lists = [];

        foreach ($knittingPayments as $knittingPayment) {

            $amount = KnittingPayment::where('knitting_party_id', $request->knitting_party_id)
                ->where('id', '<=', $knittingPayment->id)
                ->sum(DB::raw('IFNULL(debit,0) - IFNULL(credit,0)'));

            $lists[] = [
                'id' => $knittingPayment->id,
                'date' => $knittingPayment->date,
                'particulars' => $knittingPayment->particulars,
                'debit' => $knittingPayment->debit,
                'credit' => $knittingPayment->credit,
                'amount' => $amount
            ];
        }

        return Inertia::render('Knittings/KnittingParty/KnittingPaymentListPage', ['knittingPayment' => $lists, 'knittingParty' => $knittingParty]);
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
                'credit' => $request->amount,
                'particulars' => $request->particulars,
                'date' => $request->date
            ]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Payment Saved Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //delete payment
    public function knittingPaymentDelete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $knittingPayment = KnittingPayment::findOrFail($id);

            $knittingParty = KnittingParty::findOrFail($knittingPayment->knitting_party_id);

            if ($knittingPayment->debit) {
                $knittingParty->decrement('due_amount', $knittingPayment->debit);
            } else if ($knittingPayment->credit) {
                $knittingParty->increment('due_amount', $knittingPayment->credit);
            }

            $knittingPayment->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Payment Deleted Successfully', 'error' => '']);
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
