<?php

namespace App\Services\Knitting;

use Exception;
use App\Models\Customer;
use App\Models\KnittingSale;
use App\Models\CustomerPayment;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;
use App\Models\KnittingSaleProduct;


class KnittingSaleService
{
    public function createKnittingSale($request)
    {

        DB::beginTransaction();
        try {
            $knittingSale = KnittingSale::create([
                'challan_no' => $request->challan_no,
                'customer_id' => $request->customer_id,
                'unit' => $request->total_unit,
                'total_amount' => $request->total_amount,
                'sale_date' => $request->sale_date
            ]);

            foreach ($request->knittingReceives as $knitting) {
                KnittingSaleProduct::create([
                    'knitting_sale_id' => $knittingSale->id,
                    'knitting_receive_id' => $knitting['knitting_receive_id'],
                    'unit' => $knitting['weight'],
                    'price' => $knitting['price'],
                    'total_amount' => $knitting['sale_price']

                ]);

                KnittingReceive::find($knitting['knitting_receive_id'])->decrement('available_unit', $knitting['weight']);
            }

            $customer = Customer::find($request->customer_id);
            $customer->increment('due_amount', $request->total_amount);

            CustomerPayment::create([
                'customer_id' => $request->customer_id,
                'amount' => $customer->due_amount,
                'credit' => $request->total_amount,
                'challan_no' => $request->challan_no,
                'challan_type' => 'knitting',
                'particulars' => 'Knitting Sale',
                'date' => $request->sale_date
            ]);

            DB::commit();
            return;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong");
        }
    }
}
