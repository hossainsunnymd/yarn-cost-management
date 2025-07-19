<?php

namespace App\Services\Knitting;

use Exception;
use App\Models\Knitting;
use App\Models\KnittingYarn;
use App\Models\YarnPurchase;
use Illuminate\Support\Facades\DB;

class KnittingService{
     public function createKnitting( $request)
    {

        DB::beginTransaction();
        try {
            $perUnitCost = $request->total / $request->total_weight;

            $data = Knitting::create([
                'knitting_party_id' => $request->knitting_party_id,
                'role' => $request->role,
                'total_unit' => $request->total_weight,
                'available_unit' => $request->total_weight,
                'total_cost' => $request->total,
                'per_unit_cost' => $perUnitCost,
                'fabric_name' => $request->fabric_name,
            ]);

            foreach ($request->yarns as $yarn) {
                KnittingYarn::create([
                    'knitting_id' => $data->id,
                    'yarn_purchase_id' => $yarn['id'],
                    'unit' => $yarn['weight'],
                    'per_unit_cost' => $yarn['per_unit_cost'],
                ]);


                $yarnPurchase = YarnPurchase::findOrFail($yarn['id']);

                $yarnPurchase->decrement('available_unit', $yarn['weight']);

                $perUnitCost = $yarnPurchase->per_unit_cost;
                $currentTotalAmount = $yarnPurchase->available_unit * $perUnitCost;

                $yarnPurchase->update(['current_total_amount' => $currentTotalAmount]);
            }


            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Something went wrong');
        }
    }
}
