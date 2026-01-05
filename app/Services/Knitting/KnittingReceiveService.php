<?php

namespace App\Services\Knitting;

use Exception;
use App\Models\Knitting;
use App\Models\KnittingParty;
use App\Models\KnittingPayment;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;

class KnittingReceiveService{
    public function createKnittingReceive($request)
{
    $knitting = Knitting::findOrFail($request->knitting_id);

    if ($knitting->available_unit < $request->unit) {
        throw new Exception('You cannot receive more unit than available unit');
    }

    DB::beginTransaction();
    try {

        $knittingPartyId = $knitting->knitting_party_id;
        $perUnitKnittingCost = $knitting->per_unit_cost;

        // ✅ ALWAYS define unit first
        $unit = $request->unit;
        $wastage = $request->wastage ?? 0;

        // calculate total knitting cost
        $totalKnittingCost = $request->unit * $request->per_unit_knitting_cost;

        // base calculation
        $receivedKnittingUnitCost =
            ($request->unit * $perUnitKnittingCost) + $totalKnittingCost;

        // if wastage exists
        if ($wastage > 0) {
            $receivedKnittingUnitCost =
                (($request->unit + $wastage) * $perUnitKnittingCost) + $totalKnittingCost;

            $unit = $request->unit - $wastage; // ✅ overwrite safely
        }

        $receivePerUnitCost = $receivedKnittingUnitCost / $unit;

        DB::beginTransaction();
        try {
            $unit = $request->unit;
            $knittingPartyId = $knitting->knitting_party_id;
            $perUnitKnittingCost = $knitting->per_unit_cost;

        // update knitting stock
        $knitting->decrement('available_unit', $request->unit + $wastage);

        // update party due
        KnittingParty::find($knittingPartyId)
            ->increment('due_amount', $totalKnittingCost);

        // payment log
        KnittingPayment::create([
            'knitting_party_id' => $knittingPartyId,
            'debit' => $totalKnittingCost,
            'particulars' => 'Knitting Receive',
            'challan_no' => $knitting->challan_no,
            'date' => now()->format('Y-m-d'),
        ]);

        DB::commit();
        return true;

                $unit = $request->unit - $request->wastage;
            }

            $data = [
                'knitting_id' => $request->knitting_id,
                'total_cost' => $receivedKnittingUnitCost,
                'unit' => $unit,
                'available_unit' => $unit,
                'knitting_cost' => $totalKnittingCost,
                'per_unit_cost' => $receivePerUnitCost,
                'roll' => $request->roll,
                'wastage' => $request->wastage

            ];

            KnittingReceive::create($data);
            $knitting->decrement('available_unit', $request->unit);
            $knittingParty=KnittingParty::find($knittingPartyId);
            $knittingParty->increment('due_amount', $totalKnittingCost);
            KnittingPayment::create([
                'knitting_party_id' => $knittingPartyId,
                'debit' => $totalKnittingCost,
                'particulars'=>'Knitting Receive',
                'challan_no'=>$knitting->challan_no,
                'date'=>date('Y-m-d')
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}


