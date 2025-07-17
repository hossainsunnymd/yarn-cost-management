<?php

namespace App\Services\Sewing;

use Exception;
use App\Models\Sewing;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;


class SewingService{

     public function createSewing($request)
    {

        //check available unit
        $cutting = CuttingReceive::findOrFail($request->cutting_receive_id);
        if ($cutting->available_unit < $request->unit) {
            throw new Exception("Pcs Not Available");
        }


        DB::beginTransaction();
        try {
            $data = [
                'cutting_receive_id' => $request->cutting_receive_id,
                'sewing_party_id' => $request->sewing_party_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
            ];

            Sewing::create($data);
            $cutting = CuttingReceive::findOrFail($request->cutting_receive_id);
            $cutting->decrement('available_unit', $request->unit);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
           throw new Exception('Something went wrong');
        }
    }
}
