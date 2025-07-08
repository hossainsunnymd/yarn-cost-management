<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Dyeing;
use App\Models\DyeingParty;
use Illuminate\Http\Request;
use App\Models\DyeingReceive;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DyeingController extends Controller
{

    //dyeing list
    public function dyeingList()
    {

        $dyeingList = Dyeing::with('dyeingParty')->get();
        return Inertia::render('Dyeings/Dyeing/DyeingListPage', ['dyeingList' => $dyeingList]);
    }

    //dyeing save page
    public function dyeingSavePage(Request $request)
    {
        $dyeingPartyList = DyeingParty::all();
        return Inertia::render('Dyeings/Dyeing/DyeingSavePage', ['dyeingPartyList' => $dyeingPartyList]);
    }

    //create dyeing
    public function createDyeing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dyeing_party_id' => 'required',
            'unit' => 'required|numeric',
            'color' => 'required',
            'roll' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()]);
        }

        //check knitting receive available unit
        $knittingReceive = KnittingReceive::findOrFail($request->knitting_receive_id);
        if ($knittingReceive->available_unit < $request->unit) {
            return redirect()->back()->with(['status' => false, 'message' => 'Knitting Receive Available Unit Not Available', 'error' => '']);
        }

        DB::beginTransaction();
        try {

            $data = [
                'dyeing_party_id' => $request->dyeing_party_id,
                'knitting_receive_id' => $request->knitting_receive_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'color' => $request->color,
                'roll' => $request->roll,
                'design_name' => $request->design_name
            ];
            Dyeing::create($data);
            $knittingReceive = KnittingReceive::findOrFail($request->knitting_receive_id);
            $knittingReceive->decrement('available_unit', $request->unit);
            $knittingReceive->decrement('roll', $request->roll);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
    //dyeing receive page
    public function dyeingReceivePage()
    {
        return Inertia::render('Dyeings/Dyeing/DyeingReceivePage');
    }

    //create dyeing receive
    public function createDyeingReceive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|numeric',
            'dyeing_cost' => 'required|numeric',
            'roll' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()]);
        }

        //check is dyeing  unit available
        $dyeing = Dyeing::find($request->dyeing_id);
        if ($dyeing->available_unit < $request->unit) {
            return redirect()->back()->with(['status' => false, 'message' => 'Dyeing Unit Not Available', 'error' => '']);
        }

        DB::beginTransaction();
        try {
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
                'dyeing_cost' => $request->dyeing_cost,
                'roll' => $request->roll

            ];

            DyeingReceive::create($data);
            $dyeing = Dyeing::findOrFail($request->dyeing_id);
            $dyeing->decrement('available_unit', $request->unit);
            $dyeing->decrement('roll', $request->roll);
            DyeingParty::find($dyeingPartyId)->increment('due_amount', $request->dyeing_cost);

            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
}
