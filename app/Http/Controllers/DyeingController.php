<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Drying;
use App\Models\DryingParty;
use App\Models\Knitting;
use Illuminate\Http\Request;

class DyeingController extends Controller
{
    //dyeing party list
    public function dyeingPartyList(){
        $dyeingPartyList = DryingParty::all();
        return Inertia::render('Dyeing/DyeingPartyListPage',['dyeingPartyList' => $dyeingPartyList]);
    }

    //dyeing save page
    public function dyeingPartySavePage(Request $request) {
        $dyeingParty=DryingParty::find($request->dyeing_party_id);
        return Inertia::render('Dyeing/DyeingPartySavePage',['dyeingParty' => $dyeingParty]);
    }

    //create dyeing party
    public function createDyeingParty(Request $request) {
        $data=[
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'total_amount'=>0,
            'due_amount'=>0
        ];
        DryingParty::create($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Created Successfully','error' => '']);
    }

    //update dyeing party
    public function updateDyeingParty(Request $request) {
        $data=[
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'total_amount'=>0,
            'due_amount'=>0
        ];
        DryingParty::find($request->dyeing_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Updated Successfully','error' => '']);
    }

    //delete dyeing party
    public function dyeingPartyDelete(Request $request) {
        DryingParty::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Deleted Successfully','error' => '']);
    }

    //dyeing list
    public function dyeingList(){

        $dyeingList = Drying::with('knitting.yarnPurchase','dryingParty')->get();
        return Inertia::render('Dyeing/DyeingListPage',['dyeingList' => $dyeingList]);
    }

    //dyeing save page
    public function dyeingSavePage(Request $request) {
        $dyeingPartyList = DryingParty::all();
        return Inertia::render('Dyeing/DyeingSavePage',['dyeingPartyList' => $dyeingPartyList]);
    }

    //create dyeing
    public function createDyeing(Request $request) {

        $data=[
            'drying_party_id'=>$request->dyeing_party_id,
            'knitting_id'=>$request->knitting_id,
            'unit'=>$request->unit,
            'total_amount'=>$request->total_amount,
        ];
         Drying::create($data);
        Knitting::where('id',$request->knitting_id)->decrement('unit', $request->unit);

        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Created Successfully','error' => '']);
    }

}
