<?php

namespace App\Services\Cutting;

use Exception;
use App\Models\Cutting;
use App\Models\CuttingParty;
use App\Models\DyeingReceive;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;

class CuttingReceiveService
{
    public function createCuttingReceive($request)
    {

        DB::beginTransaction();
        try {
            $cutting = Cutting::findOrFail($request->cutting_id);
            $totalUnit = $cutting->unit;
            $cuttingPartyId = $cutting->cutting_party_id;
            $perUnitCost = DyeingReceive::find($cutting->dyeing_receive_id)->per_unit_cost;

            //calculate total cutting cost
            $totalCuttingCost=$request->unit*$request->per_unit_cutting_cost;

            //calculate total unit cost
            $totalUnitCost = ($totalUnit * $perUnitCost) + $totalCuttingCost;

            //calculate per unit cost
            $perPcCost = $totalUnitCost / $request->unit;

            $data = [
                'cutting_id' => $request->cutting_id,
                'total_cost' => $totalUnitCost,
                'per_unit_cost' => $perPcCost,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'cutting_cost' => $totalCuttingCost,
            ];

            CuttingReceive::create($data);
            $receive = Cutting::findOrFail($request->cutting_id);
            $receive->decrement('available_unit', $cutting->unit);
            CuttingParty::find($cuttingPartyId)->increment('due_amount', $totalCuttingCost);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
