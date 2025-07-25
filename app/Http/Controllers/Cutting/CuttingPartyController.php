<?php

namespace App\Http\Controllers\Cutting;

use Exception;
use Inertia\Inertia;
use App\Models\Cutting;
use App\Models\CuttingParty;
use Illuminate\Http\Request;
use App\Models\CuttingPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CuttingPartyController extends Controller
{
    //cutting party list
    public function CuttingPartyList()
    {
        $cuttingParties = CuttingParty::all();
        return Inertia::render('Cuttings/CuttingParty/CuttingPartyListPage', ['cuttingParties' => $cuttingParties]);
    }

    //cutting party detail list
    public function CuttingPartyDetailList(Request $request)
    {
        $cutting=Cutting::where('cutting_party_id',$request->cutting_party_id)->with('cuttingParty')->get();
        $cuttingPayments=CuttingPayment::where('cutting_party_id',$request->cutting_party_id)->latest()->first();
        return Inertia::render('Cuttings/CuttingParty/CuttingPartyDetailListPage', ['cutting' => $cutting,'cuttingPayments'=>$cuttingPayments]);
    }

    //sewing party save page
    public function cuttingPartySavePage(Request $request)
    {

        $cuttingParty = CuttingParty::find($request->sewing_party_id);
        return Inertia::render('Cuttings/CuttingParty/CuttingPartySavePage', ['cuttingParty' => $cuttingParty]);
    }

    //sewing party save
    public function createCuttingParty(Request $request)
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
            CuttingParty::create($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Party Created Successfully', 'error' => '']);
        }
    }

    //sewing party update
    public function updateCuttingParty(Request $request)
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
            CuttingParty::where('id', $request->cutting_party_id)->update($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Party Updated Successfully', 'error' => '']);
        }
    }

    //cutting payment list
    public function CuttingPaymentList(Request $request)
    {

        $cuttingPayment = CuttingPayment::where('cutting_party_id', $request->cutting_party_id)->with('cuttingParty')->get();
        $totalPayment = CuttingPayment::where('cutting_party_id', $request->cutting_party_id)->sum('amount');
        return Inertia::render('Cuttings/CuttingParty/CuttingPaymentListPage', ['cuttingPayment' => $cuttingPayment, 'totalPayment' => $totalPayment]);
    }

    //cutting payment
    public function saveCuttingPayment(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'amount'=>'required|numeric|min:1',
        ]);

        if($validation->fails()){
            return redirect()->back()->with(['message' => 'Please Enter valid amount']);
        }

        DB::beginTransaction();
        try {
            $cuttingParty = CuttingParty::find($request->cutting_party_id);
            $cuttingParty->decrement('due_amount', $request->amount);
            CuttingPayment::create([
                'cutting_party_id' => $request->cutting_party_id,
                'amount' => $request->amount
            ]);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Payment Saved Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //sewing party delete
    public function cuttingPartyDelete(Request $request)
    {
        try {
            CuttingParty::findOrFail($request->cutting_party_id)->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Party Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }
}
