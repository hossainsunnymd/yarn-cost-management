<?php

namespace App\Services\Cutting;

use Exception;
use App\Models\Cutting;
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
            $perUnitCost = DyeingReceive::find($cutting->dyeing_receive_id)->per_unit_cost;

            //calculate total unit cost
            $totalUnitCost = ($totalUnit * $perUnitCost) + $request->cutting_cost ?? 0;


            //calculate per unit cost
            $perPcCost = $totalUnitCost / $request->unit;


            $data = [
                'cutting_id' => $request->cutting_id,
                'total_cost' => $totalUnitCost,
                'per_unit_cost' => $perPcCost,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'cutting_cost' => $request->cutting_cost
            ];

            CuttingReceive::create($data);
            $receive = Cutting::findOrFail($request->cutting_id);
            $receive->decrement('available_unit', $cutting->unit);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
