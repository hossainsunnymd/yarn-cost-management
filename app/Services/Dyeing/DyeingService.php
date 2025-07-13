<?php

namespace App\Services\Dyeing;

use Exception;
use App\Models\Dyeing;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;


class DyeingService
{
    public function createDyeing($request)
    {
        //check knitting receive available unit
        $knittingReceive = KnittingReceive::findOrFail($request->knitting_receive_id);
        if ($knittingReceive->available_unit < $request->unit) {
            throw new Exception("Unit Not Available");
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
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
