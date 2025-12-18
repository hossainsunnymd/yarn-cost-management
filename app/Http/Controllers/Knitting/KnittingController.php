<?php

namespace App\Http\Controllers\Knitting;

use Exception;
use Inertia\Inertia;
use App\Models\Knitting;
use App\Models\KnittingYarn;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\KnittingPayment;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Knitting\KnittingService;
use App\Services\Knitting\KnittingReceiveService;
use App\Services\RecalculationPayments\RecalculateKnittingPaymentService;

class KnittingController extends Controller
{

    //knitting list
    public function knittingList()
    {
        $knittingList = Knitting::with('knittingYarn.yarnPurchase', 'knittingParty')->get();
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
    public function createKnitting(KnittingService $knittingService, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'challan_no' => 'unique:knittings,challan_no',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['status'=>false,'message'=>'Challan no already exist']);
        }

        try {
            $knittingService->createKnitting($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting created successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }

    //delete knitting
    public function knittingDelete(Request $request)
    {

        DB::beginTransaction();
        try {
            $knittingYarn = KnittingYarn::where('knitting_id', $request->knitting_id)->get();
            foreach ($knittingYarn as $yarn) {
                $yarnPurchase = YarnPurchase::find($yarn->yarn_purchase_id);
                $yarnPurchase->increment('available_unit', $yarn->unit);
                $currentTotalAmount = $yarnPurchase->unit * $yarnPurchase->per_unit_cost;
                $yarnPurchase->update(['current_total_amount' => $currentTotalAmount]);

            }

            KnittingYarn::where('knitting_id', $request->knitting_id)->delete();
            Knitting::find($request->knitting_id)->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting deleted successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }

    //knitting receive list
    public function knittingReceiveList()
    {
        $knittingReceiveList = KnittingReceive::with('knitting')->get();
        return Inertia::render('Knittings/Knitting/KnittingReceiveListPage', ['knittingReceiveList' => $knittingReceiveList]);
    }

    //knitting receive page
    public function knittingReceivePage(Request $request)
    {
        $knitting = Knitting::find($request->knitting_id);
        return Inertia::render('Knittings/Knitting/KnittingReceivePage', ['knitting' => $knitting]);
    }

    //create knitting receive
    public function createKnittingReceive(KnittingReceiveService $knittingReceiveService, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'unit' => 'required',
            'per_unit_knitting_cost' => 'required',
            'roll' => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        }

        try {
            $knittingReceiveService->createKnittingReceive($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }

    //delete knitting receive
    public function deleteKnittingReceive(Request $request)
    {
        DB::beginTransaction();
         try {
            $knittingReceive=KnittingReceive::find($request->knitting_receive_id)->with('knitting')->first();
            Knitting::increment('available_unit', $knittingReceive->available_unit);
            KnittingPayment::where('challan_no', $knittingReceive->knitting->challan_no)->delete();
            RecalculateKnittingPaymentService::recalculateKnittingPayment($knittingReceive->knitting->knitting_party_id);
            $knittingReceive->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting Receive deleted successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }
}
