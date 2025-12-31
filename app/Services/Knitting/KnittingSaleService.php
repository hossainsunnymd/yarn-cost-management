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
                'total_unit' => $request->total_unit,
                'total_amount' => $request->total_amount,
                'sale_date' => $request->sale_date,
                'total_cost' => $request->total_cost

            ]);

            foreach ($request->knittingReceives as $knittingReceive) {
                KnittingSaleProduct::create([
                    'knitting_sale_id' => $knittingSale->id,
                    'knitting_receive_id' => $knittingReceive['id'],
                    'unit' => $knittingReceive['weight'],
                    'price' => $knittingReceive['price'],
                    'total_amount' => $knittingReceive['sale_price'],
                    'per_unit_cost' => $knittingReceive['per_unit_cost']

                ]);

                KnittingReceive::find($knittingReceive['id'])->decrement('available_unit', $knittingReceive['weight']);
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
            throw new Exception($e->getMessage());
        }
    }
}
