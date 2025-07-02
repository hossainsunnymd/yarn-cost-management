<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\CuttingParty;
use App\Models\CuttingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CuttingPartyController extends Controller
{
    //cutting party list
    public function cuttingPartyList(){
        $cuttingParties = CuttingParty::all();
        $lastCuttingPayment=CuttingPayment::latest()->first();
        return Inertia::render('CuttingParty/CuttingPartyListPage', ['cuttingParties' => $cuttingParties,'lastCuttingPayment'=>$lastCuttingPayment]);
    }

    //cutting party save page
    public function cuttingPartySavePage(Request $request){

        $cuttingParty = CuttingParty::find($request->cutting_party_id);
        return Inertia::render('CuttingParty/CuttingPartySavePage', ['cuttingParty' => $cuttingParty]);
    }

    //cutting party save
    public function createCuttingParty(Request $request){

        $validation=Validator::make($request->all(),[
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with(['errors'=>$validation->errors()]);
        }else{
            $data=[
                'name'=>$request->name,
                'address'=>$request->address,
                'phone'=>$request->phone,
                'due_amount'=>0
            ];
            CuttingParty::create($data);
            return redirect()->back()->with(['status'=>true,'message'=>'Cutting Party Created Successfully','error'=>'']);

        }

    }

    //update cutting party
    public function updateCuttingParty(Request $request){

        $validation=Validator::make($request->all(),[
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with(['errors'=>$validation->errors()]);
        }else{
            $data=[
                'name'=>$request->name,
                'address'=>$request->address,
                'phone'=>$request->phone,
            ];
            CuttingParty::where('id',$request->cutting_party_id)->update($data);
            return redirect()->back()->with(['status'=>true,'message'=>'Cutting Party Updated Successfully','error'=>'']);

        }

    }


    //cutting payment

    public function cuttingPayment(Request $request){

        $validation=Validator::make($request->all(),[
            'cutting_party_id' => 'required',
            'amount' => 'required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with(['errors'=>$validation->errors()]);
        }

        $data=[
            'cutting_party_id'=>$request->cutting_party_id,
            'amount'=>$request->amount,
        ];
        CuttingPayment::create($data);
        $cuttingParty=CuttingParty::findOrFail($request->cutting_party_id);
        $cuttingParty->decrement('due_amount',$request->amount);
        return redirect()->back()->with(['status'=>true,'message'=>'Cutting Payment Created Successfully','error'=>'']);
    }

    //delete cutting party
    public function deleteCuttingParty(Request $request){
        CuttingParty::find($request->cutting_party_id)->delete();
        return redirect()->back()->with(['status'=>true,'message'=>'Cutting Party Deleted Successfully','error'=>'']);
    }
}

