<?php

namespace App\Services\Dyeing;

use Exception;
use App\Models\Dyeing;
use App\Models\DyeingParty;
use App\Models\DyeingReceive;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;

class DyeingReceiveService
{
    public function createDyeingReceive($request)
    {
        //check is dyeing  unit available
        $dyeing = Dyeing::find($request->dyeing_id);
        if ($dyeing->available_unit < $request->unit) {
            throw new Exception('Dyeing unit not available');
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
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
