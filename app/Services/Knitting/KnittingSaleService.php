<?php

namespace App\Services\Knitting;

use Exception;
use App\Models\KnittingSale;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;


class KnittingSaleService
{
    public function createKnittingSale($request)
    {
        //check available unit
        $knittingReceive = KnittingReceive::findOrFail($request->knitting_receive_id);
        if ($request->unit > $knittingReceive->available_unit) {
           throw new Exception('Unit not available');
        }
        DB::beginTransaction();
        try {
            $data = [
                'knitting_receive_id' => $request->knitting_receive_id,
                'unit' => $request->unit,
                'total_amount' => $request->total_amount,
            ];
            KnittingSale::create($data);
            KnittingReceive::where('id', $request->knitting_receive_id)->decrement('available_unit', $request->unit);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
