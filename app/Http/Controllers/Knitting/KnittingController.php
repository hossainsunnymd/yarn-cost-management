<?php

namespace App\Http\Controllers\Knitting;

use Exception;
use Inertia\Inertia;
use App\Models\Knitting;
use App\Models\YarnPurchase;
use Illuminate\Http\Request;
use App\Models\KnittingParty;
use App\Models\KnittingReceive;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Knitting\KnittingService;
use App\Services\Knitting\KnittingReceiveService;

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
    public function createKnitting(KnittingService $knittingService, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'knitting_party_id' => 'required',
            'yarns' => 'required',
            'total' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }

        try {
            $knittingService->createKnitting($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting created successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
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
    public function createKnittingReceive(KnittingReceiveService $knittingReceiveService, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'unit' => 'required',
            'knitting_cost' => 'required',
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
}
