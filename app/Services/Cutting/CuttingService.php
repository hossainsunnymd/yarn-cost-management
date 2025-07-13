<?php

namespace App\Services\Cutting;

use Exception;
use App\Models\Cutting;
use App\Models\DyeingReceive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CuttingService {
    public function createCutting($request)
    {

        $cutting = DyeingReceive::findOrFail($request->dyeing_receive_id);
        if ($cutting->available_unit < $request->unit) {
            throw new Exception("Unit Not Available");
        }

        DB::beginTransaction();
        try {
            Cutting::create([
                'dyeing_receive_id' => $request->dyeing_receive_id,
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
