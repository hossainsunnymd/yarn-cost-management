<?php


namespace App\Services\RecalculationPayments;

use Exception;
use App\Models\SewingParty;
use App\Models\SewingPayment;
use Illuminate\Support\Facades\DB;


class RecalculateSewingPaymentService
{

    public static function recalculateSewingPayment($sewingPartyId)
    {

        DB::beginTransaction();
        try {
            $sewingParty = SewingParty::find($sewingPartyId);

            $balance = 0;
            SewingPayment::where('sewing_party_id', $sewingPartyId)
                ->chunkById(1000, function ($sewingPayments) use (&$balance) {

                    foreach ($sewingPayments as $sewingPayment) {
                        $debit = $sewingPayment->debit ?? 0;
                        $credit = $sewingPayment->credit ?? 0;
                        $balance = $balance + $debit - $credit;
                        $sewingPayment->update(['amount' => $balance]);
                    }
                });

            $sewingParty->update(['due_amount' => $balance]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
