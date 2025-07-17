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

        //check is dyeing  roll available
        if ($dyeing->roll < $request->roll) {
            throw new Exception('Dyeing roll not available');
        }

        DB::beginTransaction();
        try {
            $unit = $request->unit;
            $dyeing = Dyeing::find($request->dyeing_id);
            $dyeingPartyId = $dyeing->dyeing_party_id;
            $perUnitknittingReceiveCost = KnittingReceive::find($dyeing->knitting_receive_id)->per_unit_cost;

            //calculate total dyeing cost
            $totalDyeingCost =$request->unit * $request->per_unit_dyeing_cost;

            //calculate received dyeing unit cost
            $receivedDyeingUnitCost = ($request->unit * $perUnitknittingReceiveCost) + $totalDyeingCost;
            $receiveDyeingPerUnitCost = $receivedDyeingUnitCost / $request->unit;

            if ($request->wastage > 0) {
                $receivedDyeingUnitCost = ($request->unit * $perUnitknittingReceiveCost) + $totalDyeingCost;
                $receiveDyeingPerUnitCost = $receivedDyeingUnitCost / ($request->unit - $request->wastage);

                $unit = $request->unit - $request->wastage;
            }

            $data = [
                'dyeing_id' => $request->dyeing_id,
                'total_cost' => $receivedDyeingUnitCost,
                'per_unit_cost' => $receiveDyeingPerUnitCost,
                'unit' => $unit,
                'available_unit' => $unit,
                'wastage' => $request->wastage,
                'dyeing_cost' => $totalDyeingCost,
                'roll' => $request->roll

            ];

            DyeingReceive::create($data);
            $dyeing = Dyeing::findOrFail($request->dyeing_id);
            $dyeing->decrement('available_unit', $request->unit);
            $dyeing->decrement('roll', $request->roll);
            DyeingParty::find($dyeingPartyId)->increment('due_amount', $totalDyeingCost);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
