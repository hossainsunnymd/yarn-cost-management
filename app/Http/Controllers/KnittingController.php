<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Knitting;
use App\Models\KnittingSale;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\KnittingPayment;
use App\Models\KnittingReceive;
use App\Models\KnittingYarn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class KnittingController extends Controller
{

    //knitting list
    public function knittingList()
    {
        $knittingList = Knitting::with('knittingYarn', 'knittingParty')->get();
        return Inertia::render('Knittings/Knitting/KnittingListPage', ['knittingList' => $knittingList]);
    }

    //knitting save page
    public function knittingSavePage(Request $request)
    {
        $knittingPartyList = KnittingParty::all();
        $yarnPurchaseList = YarnPurchase::all();
        return Inertia::render('Knittings/Knitting/KnittingSavePage', ['knittingPartyList' => $knittingPartyList, 'yarnPurchaseList' => $yarnPurchaseList]);
    }

    //create knitting
    public function createKnitting(Request $request)
    {

        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'knitting_party_id' => 'required',
                'yarns' => 'required',
                'total' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with(['errors' => $validator->errors()]);
            }

            $perUnitCost = $request->total / $request->total_weight;

            $data = Knitting::create([
                'knitting_party_id' => $request->knitting_party_id,
                'role' => $request->role,
                'total_unit' => $request->total_weight,
                'available_unit' => $request->total_weight,
                'total_cost' => $request->total,
                'per_unit_cost' => $perUnitCost
            ]);

            foreach ($request->yarns as $yarn) {
                KnittingYarn::create([
                    'knitting_id' => $data->id,
                    'yarn_purchase_id' => $yarn['id'],
                    'unit' => $yarn['weight'],
                    'per_unit_cost' => $yarn['per_unit_cost'],
                ]);


                $yarnPurchase = YarnPurchase::find($yarn['id']);

                $yarnPurchase->decrement('available_unit', $yarn['weight']);

                $perUnitCost = $yarnPurchase->per_unit_cost;
                $currentTotalAmount = $yarnPurchase->available_unit * $perUnitCost;

                $yarnPurchase->update(['current_total_amount' => $currentTotalAmount]);
            }


            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['status' => false, 'message' => 'Knitting Created Failed', 'error' => $e->getMessage()]);
        }
    }

    //knitting receive list
    public function knittingReceiveList()
    {
        $knittingReceiveList = KnittingReceive::get();
        return Inertia::render('Knittings/Knitting/KnittingReceiveListPage', ['knittingReceiveList' => $knittingReceiveList]);
    }

    //knitting receive page
    public function knittingReceivePage(Request $request)
    {

        return Inertia::render('Knittings/Knitting/KnittingReceivePage');
    }

    //create knitting receive
    public function createKnittingReceive(Request $request)
    {

        DB::beginTransaction();
        try {
            $knitting = Knitting::find($request->knitting_id);
            $knittingPartyId = $knitting->knitting_party_id;
            $perUnitKnittingCost = $knitting->per_unit_cost;


            //calculate received knitting unit cost
            $receivedKnittingUnitCost = ($request->unit * $perUnitKnittingCost) + $request->knitting_receive_cost;
            $receivePerUnitCost = $receivedKnittingUnitCost / $request->unit;

            if ($request->wastage > 0) {

                $receivedKnittingUnitCost = (($request->unit + $request->wastage) * $perUnitKnittingCost) + $request->knitting_receive_cost;
                $receivePerUnitCost = $receivedKnittingUnitCost / $request->unit;
            }

            $data = [
                'knitting_id' => $request->knitting_id,
                'total_cost' => $receivedKnittingUnitCost,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'knitting_cost' => $request->knitting_receive_cost,
                'per_unit_cost' => $receivePerUnitCost,
                'roll' => $request->roll,
                'wastage' => 0

            ];

            KnittingReceive::create($data);
            $knitting = Knitting::where('id', $request->knitting_id);
            $knitting->decrement('available_unit', $request->unit);
            KnittingParty::find($knittingPartyId)->increment('due_amount', $request->knitting_receive_cost);

            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }

  
}
