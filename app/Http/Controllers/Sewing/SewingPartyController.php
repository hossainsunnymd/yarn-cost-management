<?php

namespace App\Http\Controllers\Sewing;

use Exception;
use Inertia\Inertia;
use App\Models\Sewing;
use App\Models\SewingParty;
use Illuminate\Http\Request;
use App\Models\SewingPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SewingPartyController extends Controller
{
    //sewing party list
    public function sewingPartyList()
    {
        $sewingParties = SewingParty::all();
        return Inertia::render('Sewings/SewingParty/SewingPartyListPage', ['sewingParties' => $sewingParties]);
    }

    //sewing party details list
    public function sewingPartyDetailList(Request $request)
    {
        $sewings = Sewing::where('sewing_party_id', $request->sewing_party_id)->with('sewingParty')->get();
        $sewingPayments = SewingPayment::latest()->first();
        return Inertia::render('Sewings/SewingParty/SewingPartyDetailListPage', ['sewings' => $sewings, 'sewingPayments' => $sewingPayments]);
    }

    //sewing party save page
    public function sewingPartySavePage(Request $request)
    {
        $sewingParty = SewingParty::find($request->sewing_party_id);
        return Inertia::render('Sewings/SewingParty/SewingPartySavePage', ['sewingParty' => $sewingParty]);
    }

    //sewing party save
    public function createSewingParty(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        } else {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'due_amount' => 0
            ];
            SewingParty::create($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Party Created Successfully', 'error' => '']);
        }
    }

    //sewing party update
    public function updateSewingParty(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);


        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        } else {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ];
            SewingParty::where('id', $request->sewing_party_id)->update($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Party Updated Successfully', 'error' => '']);
        }
    }

    //sewing payment list
    public function sewingPaymentList(Request $request)
    {
        $sewingPayments = SewingPayment::where('sewing_party_id', $request->sewing_party_id)
        ->orderBy('id', 'asc')
        ->paginate(100)->withQueryString();
        $sewingParty = SewingParty::find($request->sewing_party_id);


        $pagination = [
            'next_page_url' => $sewingPayments->nextPageUrl(),
            'prev_page_url' => $sewingPayments->previousPageUrl(),
            'last_page_url' => $sewingPayments->lastPage(),
        ];

        $lists = [];
        foreach ($sewingPayments as $sewingPayment) {

            $amount = SewingPayment::where('sewing_party_id', $request->sewing_party_id)
                ->where('id', '<=', $sewingPayment->id)
                ->sum(DB::raw('IFNULL(debit,0) - IFNULL(credit,0)'));


            $lists[] = [
                'id' => $sewingPayment->id,
                'particulars' => $sewingPayment->particulars,
                'amount' => $amount,
                'credit' => $sewingPayment->credit,
                'debit' => $sewingPayment->debit,
                'date' => $sewingPayment->date,
                'challan_no' => $sewingPayment->challan_no
            ];
        }

        return Inertia::render('Sewings/SewingParty/SewingPaymentListPage', ['sewingPayment' => $lists, 'sewingParty' => $sewingParty, 'pagination' => $pagination]);
    }

    //sewing payment
    public function saveSewingPayment(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['message' => 'Please Enter valid amount']);
        }

        DB::beginTransaction();
        try {
            $sweingParty = SewingParty::find($request->sewing_party_id);
            $sweingParty->decrement('due_amount', $request->amount);
            SewingPayment::create([
                'particulars' => $request->particulars,
                'sewing_party_id' => $request->sewing_party_id,
                'credit' => $request->amount,
                'date' => $request->date
            ]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Payment Saved Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //sewing payment delete
    public function sewingPaymentDelete($id)
    {
        DB::beginTransaction();
        try {
            $sewingPayment = SewingPayment::findOrFail($id);

            if($sewingPayment->challan_no){
                throw new Exception("Challan No exist can't delete");
            }

            $sewingParty = SewingParty::findOrFail($sewingPayment->sewing_party_id);

            if ($sewingPayment->debit) {
                $sewingParty->decrement('due_amount', $sewingPayment->debit);
            } else if ($sewingPayment->credit) {
                $sewingParty->increment('due_amount', $sewingPayment->credit);
            }

            $sewingPayment->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Payment Deleted Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    //sewing party delete
    public function sewingPartyDelete(Request $request)
    {
        try {
            SewingParty::findOrFail($request->sewing_party_id)->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Party Deleted Successfully']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong']);
        }
    }
}
