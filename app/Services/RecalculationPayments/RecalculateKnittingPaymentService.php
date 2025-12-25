<?php


namespace App\Services\RecalculationPayments;

use Exception;
use App\Models\KnittingParty;
use App\Models\KnittingPayment;
use Illuminate\Support\Facades\DB;


class RecalculateKnittingPaymentService
{

    public static function recalculateKnittingPayment($knittingPartyId)
    {

        DB::beginTransaction();
        try {
            $knittingParty = KnittingParty::find($knittingPartyId);

            $balance = 0;
            KnittingPayment::where('knitting_party_id', $knittingPartyId)
                ->chunkById(100, function ($knittingPayments) use (&$balance) {

                    foreach ($knittingPayments as $knittingPayment) {
                        $debit = $knittingPayment->debit ?? 0;
                        $credit = $knittingPayment->credit ?? 0;
                        $balance = $balance + $debit - $credit;
                        $knittingPayment->update(['amount' => $balance]);
                    }
                });

            $knittingParty->update(['due_amount' => $balance]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
