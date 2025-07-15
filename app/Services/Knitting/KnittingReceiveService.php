<?php

namespace App\Services\Knitting;

use Exception;
use App\Models\Knitting;
use App\Models\KnittingParty;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;

class KnittingReceiveService{
    public function createKnittingReceive( $request)
    {

        //check is knitting unit available
        $knittingUnit = Knitting::findOrFail($request->knitting_id);
        if ($knittingUnit->available_unit < $request->unit) {
            throw new Exception('You cannot receive more unit than available unit');
        }


        DB::beginTransaction();
        try {
            $knitting = Knitting::findOrFail($request->knitting_id);
            $knittingPartyId = $knitting->knitting_party_id;
            $perUnitKnittingCost = $knitting->per_unit_cost;

            //calculate total knitting cost
            $totalKnittingCost=$request->unit*$request->per_unit_knitting_cost;

            //calculate received knitting unit cost
            $receivedKnittingUnitCost = ($request->unit * $perUnitKnittingCost) + $totalKnittingCost;
            $receivePerUnitCost = $receivedKnittingUnitCost / $request->unit;

            if ($request->wastage > 0) {

                $receivedKnittingUnitCost = (($request->unit + $request->wastage) * $perUnitKnittingCost) + $totalKnittingCost;
                $receivePerUnitCost = $receivedKnittingUnitCost / $request->unit;
            }

            $data = [
                'knitting_id' => $request->knitting_id,
                'total_cost' => $receivedKnittingUnitCost,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'knitting_cost' => $totalKnittingCost,
                'per_unit_cost' => $receivePerUnitCost,
                'roll' => $request->roll,
                'wastage' => $request->wastage

            ];

            KnittingReceive::create($data);
            $knitting = Knitting::where('id', $request->knitting_id);
            $knitting->decrement('available_unit', $request->unit + $request->wastage ?? 0);
            KnittingParty::find($knittingPartyId)->increment('due_amount', $totalKnittingCost);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
