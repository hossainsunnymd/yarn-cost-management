<?php


namespace App\Services\RecalculationPayments;

use Exception;
use App\Models\DyeingParty;
use App\Models\DyeingPayment;
use Illuminate\Support\Facades\DB;


class RecalculateDyeingPaymentService
{

    public static function recalculateDyeingPayment($dyeingPartyId)
    {

        DB::beginTransaction();
        try {
            $dyeingParty = DyeingParty::find($dyeingPartyId);

            $balance = 0;
            DyeingPayment::where('dyeing_party_id', $dyeingPartyId)
                ->chunkById(1000, function ($dyeingPayments) use (&$balance) {

                    foreach ($dyeingPayments as $dyeingPayment) {
                        $debit = $dyeingPayment->debit ?? 0;
                        $credit = $dyeingPayment->credit ?? 0;
                        $balance = $balance + $debit - $credit;
                        $dyeingPayment->update(['amount' => $balance]);
                    }
                });

            $dyeingParty->update(['due_amount' => $balance]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
