<?php

namespace App\Services\Sewing;

use Exception;
use App\Models\Sewing;
use App\Models\SewingReceive;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;


class SewingReceiveService{

     public function createSewingReceive($request)
    {

        //check available unit
        $sewing = Sewing::findOrFail($request->sewing_id);
        if ($sewing->available_unit < $request->unit) {
            throw new Exception("Pcs Not Available");
        }

        DB::beginTransaction();
        try {
            $unit=$request->unit;
            $sewing = Sewing::findOrFail($request->sewing_id);
            $perUnitCost = CuttingReceive::find($sewing->cutting_receive_id)->per_unit_cost;


            //calculate total sewing cost
            $totalSewingCost = $request->unit * $request->sewing_cost;

            //calulate received sewing unit cost
            $receivedSewingUnitCost = ($request->unit * $perUnitCost) + $totalSewingCost + $request->extra_cost??0;
            $receiveSewingPerUnitCost = $receivedSewingUnitCost / $request->unit;


            if ($request->wastage > 0) {

                $receivedSewingUnitCost = ($request->unit  * $perUnitCost) +  $totalSewingCost + $request->extra_cost??0;
                $receiveSewingPerUnitCost = $receivedSewingUnitCost / ($request->unit - $request->wastage);
                $unit = $unit - $request->wastage;
            }

            $data = [
                'sewing_id' => $request->sewing_id,
                'total_cost' => $receivedSewingUnitCost,
                'per_unit_cost' => $receiveSewingPerUnitCost,
                'unit' => $unit,
                'available_unit' => $unit,
                'wastage' => $request->wastage,
                'sewing_cost' => $totalSewingCost,
                'extra_cost' => $request->extra_cost,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('uploads', $fileName);
                $data['image'] = $fileName;
            }


            SewingReceive::create($data);
            $receive = Sewing::findOrFail($request->sewing_id);
            $receive->decrement('available_unit', $request->unit);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
