<?php

namespace App\Http\Controllers\Dyeing;

use Exception;
use Inertia\Inertia;
use App\Models\Dyeing;
use App\Models\DyeingParty;
use Illuminate\Http\Request;
use App\Models\DyeingPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class DyeingPartyController extends Controller
{
    //dyeing party list
    public function dyeingPartyList()
    {
        $dyeingPartyList = DyeingParty::with('dyeings')->get();
        return Inertia::render('Dyeings/DyeingParty/DyeingPartyListPage', ['dyeingPartyList' => $dyeingPartyList]);
    }

    //dyeing party details list
    public function dyeingPartyDetailList(Request $request)
    {
        $dyeings = Dyeing::where('dyeing_party_id', $request->dyeing_party_id)->with('dyeingParty')->get();
        $dyeingPayments = DyeingPayment::latest()->first();
        return Inertia::render('Dyeings/DyeingParty/DyeingPartyDetailListPage', ['dyeings' => $dyeings, 'dyeingPayments' => $dyeingPayments]);
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
            'due_amount' => 0,
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
            'due_amount' => 0
        ];
        DyeingParty::find($request->dyeing_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Updated Successfully', 'error' => '']);
    }

    //dyeing payment list
    public function dyeingPaymentList(Request $request)
    {
        $dyeingPayments = DyeingPayment::where('dyeing_party_id', $request->dyeing_party_id)
        ->orderBy('id', 'asc')
        ->paginate(100)->withQueryString();
        $dyeingParty = DyeingParty::find($request->dyeing_party_id);

        $pagination=[
            'next_page_url'=>$dyeingPayments->nextPageUrl(),
            'prev_page_url'=>$dyeingPayments->previousPageUrl(),
            'last_page'=>$dyeingPayments->lastPage(),
        ];

        $lists=[];
        foreach($dyeingPayments as $dyeingPayment){

            $amount=DyeingPayment::where('dyeing_party_id', $request->dyeing_party_id)
            ->where('id','<=', $dyeingPayment->id)
            ->sum(DB::raw('IFNULL(debit,0) - IFNULL(credit,0)'));

            $lists[]=[
                'id'=>$dyeingPayment->id,
                'date'=>$dyeingPayment->date,
                'particulars'=>$dyeingPayment->particulars,
                'debit'=>$dyeingPayment->debit,
                'credit'=>$dyeingPayment->credit,
                'amount'=>$amount,
                'challan_no'=>$dyeingPayment->challan_no
            ];

        }

        return Inertia::render('Dyeings/DyeingParty/DyeingPaymentListPage', ['dyeingPayment' => $lists, 'dyeingParty' => $dyeingParty, 'pagination'=>$pagination]);
    }

    //save dyeing payment
    public function saveDyeingPayment(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['message' => 'Please Enter valid amount']);
        }

        DB::beginTransaction();
        try {
            $dyeingParty = DyeingParty::find($request->dyeing_party_id);
            $dyeingParty->decrement('due_amount', $request->amount);
            DyeingPayment::create([
                'dyeing_party_id' => $request->dyeing_party_id,
                'credit' => $request->amount,
                'particulars' => $request->particulars,
                'date' => $request->date
            ]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Payment Saved Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //dyeing payment delete
    public function dyeingPaymentDelete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dyeingPayment = DyeingPayment::findOrFail($id);
            $dyeingParty = DyeingParty::find($dyeingPayment->dyeing_party_id);

            if ($dyeingPayment->debit) {
                $dyeingParty->decrement('due_amount', $dyeingPayment->debit);
            } else if ($dyeingPayment->credit) {
                $dyeingParty->increment('due_amount', $dyeingPayment->credit);
            }

            $dyeingPayment->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Payment Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //delete dyeing party
    public function dyeingPartyDelete(Request $request)
    {
        try {
            DyeingParty::findOrFail($request->dyeing_party_id)->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }
}
