<?php

namespace App\Http\Controllers\Cutting;

use Exception;
use Inertia\Inertia;
use App\Models\Cutting;
use App\Models\Category;
use App\Models\CuttingParty;
use Illuminate\Http\Request;
use App\Models\DyeingReceive;
use App\Models\CuttingPayment;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Cutting\CuttingService;
use Illuminate\Support\Facades\Validator;
use App\Services\Cutting\CuttingReceiveService;
use App\Services\RecalculationPayments\RecalculateCuttingPaymentService;

class CuttingController extends Controller
{

    //list cutting
    public function cuttingList()
    {
        $cuttings = Cutting::with('category')->get();
        return Inertia::render('Cuttings/Cutting/CuttingListPage', ['cuttings' => $cuttings]);
    }

    //cutting save page
    public function cuttingSavePage(Request $request)
    {
        $categories = Category::all();
        $cuttingParty = CuttingParty::all();
        $dyeingReceive = DyeingReceive::find($request->dyeing_receive_id);
        return Inertia::render('Cuttings/Cutting/CuttingSavePage', ['categories' => $categories, 'dyeingReceive' => $dyeingReceive, 'cuttingParty' => $cuttingParty]);
    }

    //create cutting
    public function createCutting(CuttingService $cuttingService, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_id' => 'required',
            'unit' => 'required|numeric|min:1',
            'roll' => 'required|min:1',
            'challan_no' => 'required|integer|unique:cuttings,challan_no',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with([
                'error' => $validation->errors()
            ]);
        }
        try {
            $cuttingService->createCutting($request);
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Cutting Created Successfully',
                'error' => ''
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $e->getMessage(),
                'error' => ''
            ]);
        }
    }

    //cutting delete
    public function deleteCutting(Request $request)
    {
        try {
            $cutting = Cutting::find($request->cutting_id);
            DyeingReceive::find($cutting->dyeing_receive_id)->increment('available_unit', $cutting->unit);
            DyeingReceive::find($cutting->dyeing_receive_id)->increment('roll', $cutting->roll);
            $cutting->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }


    //cutting receive list
    public function cuttingReceiveList(Request $request)
    {
        $cuttingReceives = CuttingReceive::with('cutting.category')->get();
        return Inertia::render('Cuttings/Cutting/CuttingReceiveListPage', ['cuttingReceives' => $cuttingReceives]);
    }

    //cutting receive page
    public function cuttingReceivePage(Request $request)
    {
        $cutting = Cutting::find($request->cutting_id);
        return Inertia::render('Cuttings/Cutting/CuttingReceivePage', ['cutting' => $cutting]);
    }

    //create cutting receive
    public function createCuttingReceive(CuttingReceiveService $cuttingReceiveService, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'per_unit_cutting_cost' => 'required|numeric|min:0',
            'unit' => 'required|numeric|min:1',
        ], [
            'unit.required' => 'Pcs is required',
            'per_unit_cutting_cost.required' => 'Per Unit Cutting Cost is required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        }

        try {
            $cuttingReceiveService->createCuttingReceive($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }

    //cutting receive delete
    public function deleteCuttingReceive(Request $request)
    {
        DB::beginTransaction();
        try {
            $cuttingReceive = CuttingReceive::find($request->cutting_receive_id);
            $cutting = Cutting::find($cuttingReceive->cutting_id);
            $cutting->increment('available_unit', $cutting->unit);
            CuttingPayment::where('challan_no', $cutting->challan_no)->delete();
            $cuttingReceive->delete();
            RecalculateCuttingPaymentService::recalculateCuttingPayment($cutting->cutting_party_id);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Receive Deleted Successfully', 'error' => '']);

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }
}
