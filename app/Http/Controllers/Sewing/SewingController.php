<?php

namespace App\Http\Controllers\Sewing;

use Exception;
use Inertia\Inertia;
use App\Models\Sewing;
use App\Models\SewingParty;
use Illuminate\Http\Request;
use App\Models\SewingPayment;
use App\Models\SewingReceive;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Sewing\SewingService;
use Illuminate\Support\Facades\Validator;
use App\Services\Sewing\SewingReceiveService;


class SewingController extends Controller
{
    //Sewing list
    public function sewingList()
    {
        $sewings = Sewing::with('cuttingReceive.cutting.category', 'sewingParty')->get();
        return Inertia::render('Sewings/Sewing/SewingListPage', ['sewings' => $sewings]);
    }

    //Sewing Save page
    public function sewingSavePage(Request $request)
    {
        $cuttingReceive = CuttingReceive::findOrFail($request->cutting_receive_id);

        $sewingParty = SewingParty::all();
        return Inertia::render('Sewings/Sewing/SewingSavePage', ['sewingParty' => $sewingParty, 'cuttingReceive' => $cuttingReceive]);
    }

    //Sewing create
    public function createSewing(SewingService $sewingService, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'sewing_party_id' => 'required|exists:sewing_parties,id',
            'unit' => 'required|numeric|min:1',
            'challan_no' => 'required|integer|unique:sewings,challan_no',
        ], [
            'unit.required' => 'Pcs is required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);

        }
        try {
            $sewingService->createSewing($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }

    //sewing delete
    public function sewingDelete(Request $request)
    {
        try {
            $sewing = Sewing::findOrFail($request->sewing_id);
            CuttingReceive::where('id', $sewing->cutting_receive_id)->increment('available_unit', $sewing->available_unit);
            $sewing->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //sewing receive page
    public function sewingReceivePage(Request $request)
    {
        $sewing = Sewing::findOrFail($request->sewing_id);
        return Inertia::render('Sewings/Sewing/SewingReceivePage', ['sewing' => $sewing]);
    }

    //create sewing receive
    public function createSewingReceive(SewingReceiveService $sewingReceiveService, Request $request)
    {

        $validation = Validator::make($request->all(), [
            'sewing_cost' => 'required',
            'unit' => 'required|numeric|min:1',
        ], [
            'unit.required' => 'Pcs is required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);

        }

        try {
            $sewingReceiveService->createSewingReceive($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }

    }

    //delete sewing receive
    public function sewingReceiveDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $sewingReceive = SewingReceive::findOrFail($request->sewing_receive_id);
            $sewing = Sewing::where('id', $sewingReceive->sewing_id);
            $sewing->increment('available_unit', $sewingReceive->unit);
            SewingPayment::where('chalan_no', $sewingReceive->chalan_no)->delete();
            $sewingReceive->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Receive Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }
}
