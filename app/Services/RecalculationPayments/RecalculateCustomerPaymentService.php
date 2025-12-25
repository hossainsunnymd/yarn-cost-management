<?php


namespace App\Services\RecalculationPayments;

use Exception;
use App\Models\Customer;


use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;


class RecalculateCustomerPaymentService
{

    public static function recalculateCustomerPayment($customerId)
    {

        DB::beginTransaction();
        try {
            $customer = Customer::find($customerId);

            $balance = 0;
            CustomerPayment::where('customer_id', $customerId)
                ->chunkById(100, function ($customerPayments) use (&$balance) {

                    foreach ($customerPayments as $customerPayment) {
                        $debit = $customerPayment->debit ?? 0;
                        $credit = $customerPayment->credit ?? 0;
                        $balance = $customerPayment + $debit - $credit;
                        $customerPayment->update(['amount' => $balance]);
                    }
                });

            $customer->update(['due_amount' => $balance]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
