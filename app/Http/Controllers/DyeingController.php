<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Dyeing;
use App\Models\Knitting;
use App\Models\DyeingParty;
use Illuminate\Http\Request;
use App\Models\DyeingPayment;
use App\Models\DyeingReceive;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DyeingController extends Controller
{
    //dyeing party list
    public function dyeingPartyList()
    {
        $dyeingPartyList = DyeingParty::with('dyeings')->get();
        $dyeingPayment=DyeingPayment::latest()->first();
        return Inertia::render('Dyeing/DyeingPartyListPage', ['dyeingPartyList' => $dyeingPartyList,'dyeingPayment'=>$dyeingPayment]);
    }

    //dyeing save page
    public function dyeingPartySavePage(Request $request)
    {
        $dyeingParty = DyeingParty::find($request->dyeing_party_id);
        return Inertia::render('Dyeing/DyeingPartySavePage', ['dyeingParty' => $dyeingParty]);
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
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => 0,
            'due_amount' => 0,
            'last_payment' => 0
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
            'total_amount' => 0,
            'due_amount' => 0
        ];
        DyeingParty::find($request->dyeing_party_id)->update($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Updated Successfully', 'error' => '']);
    }

    //delete dyeing party
    public function dyeingPartyDelete(Request $request)
    {
        DyeingParty::find($request->id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Party Deleted Successfully', 'error' => '']);
    }

    //dyeing list
    public function dyeingList()
    {

        $dyeingList = Dyeing::with('dyeingParty')->get();
        return Inertia::render('Dyeing/DyeingListPage', ['dyeingList' => $dyeingList]);
    }

    //dyeing save page
    public function dyeingSavePage(Request $request)
    {
        $dyeingPartyList = DyeingParty::all();
        return Inertia::render('Dyeing/DyeingSavePage', ['dyeingPartyList' => $dyeingPartyList]);
    }

    //create dyeing
    public function createDyeing(Request $request)
    {
        DB::beginTransaction();
      try{
          $validator = Validator::make($request->all(), [
            'dyeing_party_id' => 'required',
            'unit' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }

        $data = [
            'dyeing_party_id' => $request->dyeing_party_id,
            'knitting_receive_id' => $request->knitting_receive_id,
            'unit' => $request->unit,
            'available_unit' => $request->unit,
            'color' => $request->color
        ];
        Dyeing::create($data);
        KnittingReceive::where('id', $request->knitting_receive_id)->decrement('available_unit', $request->unit);
        DB::commit();
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Created Successfully', 'error' => '']);
      }catch(Exception $e){
        DB::rollBack();
        return redirect()->back()->with(['status' => false, 'message' => 'Dyeing Created Failed', 'error' => $e->getMessage()]);
      }
    }

    //dyeing receive page
    public function dyeingReceivePage()
    {
        return Inertia::render('Dyeing/DyeingReceivePage');
    }

    //create dyeing receive
    public function createDyeingReceive(Request $request)
    {
        DB::beginTransaction();
      try{
        $dyeing = Dyeing::find($request->dyeing_id);
        $dyeingPartyId = $dyeing->dyeing_party_id;
        $perUnitknittingReceiveCost = KnittingReceive::find($dyeing->knitting_receive_id)->per_unit_cost;


        //calculate received dyeing unit cost
        $receivedDyeingUnitCost = ($request->unit * $perUnitknittingReceiveCost) + $request->dyeing_cost ?? 0;
        $receiveDyeingPerUnitCost = $receivedDyeingUnitCost / $request->unit;

        if ($request->wastage > 0) {
            $receivedDyeingUnitCost = (($request->unit + $request->wastage) * $perUnitknittingReceiveCost) + $request->dyeing_cost ?? 0;
            $receiveDyeingPerUnitCost = $receivedDyeingUnitCost / $request->unit;
        }

        $data = [
            'dyeing_id' => $request->dyeing_id,
            'total_cost' => $receivedDyeingUnitCost,
            'per_unit_cost' => $receiveDyeingPerUnitCost,
            'unit' => $request->unit,
            'available_unit' => $request->unit,
            'wastage' => $request->wastage,
            'dyeing_cost' => $request->dyeing_cost

        ];

        DyeingReceive::create($data);
        Dyeing::where('id', $request->dyeing_id)->decrement('available_unit', $request->unit + $request->wastage ?? 0);
        DyeingParty::find($dyeingPartyId)->increment('due_amount', $request->dyeing_cost);

        DB::commit();
        return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Receive Created Successfully', 'error' => '']);
      }catch(Exception $e){
        DB::rollBack();
        return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
      }
    }

    //save dyeing payment
    public function saveDyeingPayment(Request $request)
    {
        $dyeingParty = DyeingParty::find($request->dyeing_party_id);
        $dyeingParty->decrement('due_amount', $request->amount);
        DyeingPayment::create([
            'dyeing_party_id' => $request->dyeing_party_id,
            'amount' => $request->amount
        ]);
        return redirect('/dyeing-party-list')->with(['status' => true, 'message' => 'Dyeing Payment Saved Successfully', 'error' => '']);
    }
}
