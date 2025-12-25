<?php


namespace App\Services\RecalculationPayments;

use Exception;
use App\Models\YarnParty;
use App\Models\YarnPayment;
use Illuminate\Support\Facades\DB;


class RecalculateYarnPaymentService
{

    public static function recalculateYarnPayment($yarnPartyId)
    {

        DB::beginTransaction();
        try {
            $yarnParty = YarnParty::find($yarnPartyId);

            $balance = 0;
            YarnPayment::where('yarn_party_id', $yarnPartyId)
                ->chunkById(100, function ($yarnPayments) use (&$balance) {

                    foreach ($yarnPayments as $yarnPayment) {
                        $debit = $yarnPayment->debit ?? 0;
                        $credit = $yarnPayment->credit ?? 0;
                        $balance = $balance + $debit - $credit;
                        $yarnPayment->update(['amount' => $balance]);
                    }
                });

            $yarnParty->update(['due_amount' => $balance]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
