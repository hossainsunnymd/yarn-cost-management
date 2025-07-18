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
        $sewings = Sewing::where('sewing_party_id',$request->sewing_party_id)->with('sewingParty')->get();
        $sewingPayments=SewingPayment::latest()->first();
        return Inertia::render('Sewings/SewingParty/SewingPartyDetailListPage', ['sewings' => $sewings,'sewingPayments'=>$sewingPayments]);
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
        $sewingPayment = SewingPayment::where('sewing_party_id', $request->sewing_party_id)->with('sewingParty')->with('sewingParty')->get();
        $totalPayment = SewingPayment::where('sewing_party_id', $request->sewing_party_id)->sum('amount');
        return Inertia::render('Sewings/SewingParty/SewingPaymentListPage', ['sewingPayment' => $sewingPayment, 'totalPayment' => $totalPayment]);
    }

    //sewing payment
    public function saveSewingPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $sweingParty = SewingParty::find($request->sewing_party_id);
            $sweingParty->decrement('due_amount', $request->amount);
            SewingPayment::create([
                'sewing_party_id' => $request->sewing_party_id,
                'amount' => $request->amount
            ]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Payment Saved Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //sewing party delete
    public function sewingPartyDelete(Request $request)
    {
        try {
            SewingParty::findOrFail($request->sewing_party_id)->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Party Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }
}
