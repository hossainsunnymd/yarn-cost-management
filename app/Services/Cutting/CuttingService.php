<?php

namespace App\Services\Cutting;

use Exception;
use App\Models\Cutting;
use App\Models\DyeingReceive;
use Illuminate\Support\Facades\DB;



class CuttingService {
    public function createCutting($request)
    {

        $cutting = DyeingReceive::findOrFail($request->dyeing_receive_id);

        //check available unit
        if ($cutting->available_unit < $request->unit) {
            throw new Exception("Unit Not Available");
        }

        //check available roll
        if ($cutting->roll < $request->roll) {
            throw new Exception("Roll Not Available");
        }

        DB::beginTransaction();
        try {
            Cutting::create([
                'dyeing_receive_id' => $request->dyeing_receive_id,
                'cutting_party_id' => $request->cutting_party_id,
                'category_id' => $request->category_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'roll' => $request->roll
            ]);

            $cutting->decrement('available_unit', $request->unit);

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
