<?php


namespace App\Services\RecalculationPayments;

use Exception;

use App\Models\CuttingParty;
use App\Models\CuttingPayment;
use Illuminate\Support\Facades\DB;


class RecalculateCuttingPaymentService
{

    public static function recalculateCuttingPayment($cuttingPartyId)
    {

        DB::beginTransaction();
        try {
            $cuttingParty = CuttingParty::find($cuttingPartyId);

            $balance = 0;
            CuttingPayment::where('cutting_party_id', $cuttingPartyId)
                ->chunkById(100, function ($cuttingPayments) use (&$balance) {

                    foreach ($cuttingPayments as $cuttingPayment) {
                        $debit = $cuttingPayment->debit ?? 0;
                        $credit = $cuttingPayment->credit ?? 0;
                        $balance = $balance + $debit - $credit;
                        $cuttingPayment->update(['amount' => $balance]);
                    }
                });

            $cuttingParty->update(['due_amount' => $balance]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
