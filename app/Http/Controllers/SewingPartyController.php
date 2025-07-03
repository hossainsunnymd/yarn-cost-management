<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\SewingParty;
use Illuminate\Http\Request;
use App\Models\SewingPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SewingPartyController extends Controller
{
    //sewing party list
    public function sewingPartyList()
    {

        $sewingParties = SewingParty::all();
        $sewingPayment=SewingPayment::latest()->first();
        return Inertia::render('SewingParty/SewingPartyListPage', ['sewingParties' => $sewingParties,'sewingPayment'=>$sewingPayment]);
    }

    //sewing party save page
    public function sewingPartySavePage(Request $request)
    {

        $sewingParty = SewingParty::find($request->sewing_party_id);
        return Inertia::render('SewingParty/SewingPartySavePage', ['sewingParty' => $sewingParty]);
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
            return redirect()->back()->with(['errors' => $validation->errors()]);
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
            return redirect()->back()->with(['errors' => $validation->errors()]);
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

    //sewing party payment
    public function saveSewingPayment(Request $request)
    {
        DB::beginTransaction();
        try{
            SewingPayment::create([
                'sewing_party_id' => $request->sewing_party_id,
                'amount' => $request->amount,
            ]);
            $sewingParty = SewingParty::findOrFail($request->sewing_party_id);
            $sewingParty->decrement('due_amount', $request->amount);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Payment Saved Successfully', 'error' => '']);
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

    //sewing party delete
    public function deleteSewingParty(Request $request)
    {
        SewingParty::where('id', $request->sewing_party_id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Sewing Party Deleted Successfully', 'error' => '']);
    }
}
