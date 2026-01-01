<?php

namespace App\Http\Controllers\Yarn;

use Exception;
use Inertia\Inertia;
use App\Models\YarnParty;
use App\Models\YarnPayment;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class YarnPartyController extends Controller
{
    //yarn party list
    public function yarnPartyList()
    {
        $yarnPartyList = YarnParty::with('yarnPurchase')->get();
        return Inertia::render('Yarn/YarnParty/YarnPartyListPage', ['yarnPartyList' => $yarnPartyList]);
    }

    //yarn party detail list
    public function yarnPartyDetailList(Request $request)
    {
        $yarnPurchases = YarnPurchase::where('yarn_party_id', $request->yarn_party_id)->with('yarnParty')->get();
        $yarnPayments = YarnPayment::latest()->first();
        return Inertia::render('Yarn/YarnParty/YarnPartyDetailListPage', ['yarnPurchases' => $yarnPurchases, 'yarnPayments' => $yarnPayments]);
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
            'due_amount' => 0,

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
            'due_amount' => 0
        ];
        YarnParty::find($request->yarn_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Updated Successfully', 'error' => '']);
    }

    //yarn payment list
    public function yarnPaymentList(Request $request)
    {
        $yarnPayments = YarnPayment::where('yarn_party_id', $request->yarn_party_id)
        ->orderBy('id', 'asc')
        ->paginate(100)->withQueryString();
        $yarnParty = YarnParty::find($request->yarn_party_id);

        $pagination=[
            'next_page_url' => $yarnPayments->nextPageUrl(),
            'prev_page_url' => $yarnPayments->previousPageUrl(),
            'last_page'=> $yarnPayments->lastPage(),
        ];

        $lists = [];
        foreach ($yarnPayments as $yarnPayment) {

            $amount = YarnPayment::where('yarn_party_id', $request->yarn_party_id)
                ->where('id', '<=', $yarnPayment->id)
                ->sum(DB::raw('IFNULL(debit,0) - IFNULL(credit,0)'));

            $lists[] = [
                'id' => $yarnPayment->id,
                'particulars' => $yarnPayment->particulars,
                'amount' => $amount,
                'credit' => $yarnPayment->credit,
                'debit' => $yarnPayment->debit,
                'date' => $yarnPayment->date,
                'challan_no' => $yarnPayment->challan_no
            ];
        }

        return Inertia::render('Yarn/YarnParty/YarnPaymentListPage', ['yarnPayments' => $lists, 'yarnParty' => $yarnParty, 'pagination' => $pagination]);
    }

    //yarn payment
    public function saveYarnPayment(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['message' => 'Please Enter valid amount']);
        }

        DB::beginTransaction();
        try {
            $yarnParty = YarnParty::findOrFail($request->yarn_party_id);
            $yarnParty->decrement('due_amount', $request->amount);
            YarnPayment::create([
                'yarn_party_id' => $request->yarn_party_id,
                'credit' => $request->amount,
                'particulars' => $request->particulars,
                'date' => $request->date

            ]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Yarn Payment Saved Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    //delete yarn payment
    public function yarnPaymentDelete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $yarnPayment = YarnPayment::findOrFail($id);
            $yarnParty = YarnParty::findOrFail($yarnPayment->yarn_party_id);
            if ($yarnPayment->debit) {
                $yarnParty->decrement('due_amount', $yarnPayment->debit);
            } else if ($yarnPayment->credit) {
                $yarnParty->increment('due_amount', $yarnPayment->credit);
            }
            $yarnPayment->delete();
            DB::commit();

            return redirect()->back()->with(['status' => true, 'message' => 'Yarn Payment Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //delete yarn party
    public function yarnPartyDelete(Request $request)
    {
        try {
            YarnParty::findOrFail($request->yarn_party_id)->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Yarn Party Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }
}
