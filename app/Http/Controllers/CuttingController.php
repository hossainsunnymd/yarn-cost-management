<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Cutting;
use App\Models\Category;
use App\Models\CuttingReceive;
use Illuminate\Http\Request;
use App\Models\DyeingReceive;
use Illuminate\Support\Facades\DB;

class CuttingController extends Controller
{

    //list cutting
    public function cuttingList()
    {
        $cuttings = Cutting::all();
        return Inertia::render('Cutting/CuttingListPage', ['cuttings' => $cuttings]);
    }

    //cutting save page
    public function cuttingSavePage()
    {
        $categories = Category::all();
        return Inertia::render('Cutting/CuttingSavePage', ['categories' => $categories]);
    }

    //create cutting
    public function createCutting(Request $request)
    {

        try {
            DB::beginTransaction();

            $data = [
                'dyeing_receive_id' => $request->dyeing_receive_id,
                'category_id' => $request->category_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
            ];

            Cutting::create($data);

            $cutting = DyeingReceive::findOrFail($request->dyeing_receive_id);
            $cutting->decrement('available_unit', $request->unit);

            DB::commit();

            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

    //cutting receive list
    public function cuttingReceiveList()
    {
        $cuttingReceives = CuttingReceive::all();
        return Inertia::render('Cutting/CuttingReceiveListPage', ['cuttingReceives' => $cuttingReceives]);
    }

    //cutting receive page
    public function cuttingReceivePage()
    {
        return Inertia::render('Cutting/CuttingReceivePage');
    }

    //create cutting receive
    public function createCuttingReceive(Request $request)
    {
        DB::beginTransaction();
        try {
            $cutting = Cutting::findOrFail($request->cutting_id);
            $perUnitCost = DyeingReceive::find($cutting->dyeing_receive_id)->per_unit_cost;

            //calulate received cutting unit cost
            $receivedCuttingUnitCost = ($request->unit * $perUnitCost) + $request->cutting_cost ?? 0;
            $receiveCuttingPerUnitCost = $receivedCuttingUnitCost / $request->unit;

            if ($request->wastage > 0) {
                $receivedCuttingUnitCost = (($request->unit + $request->wastage) * $perUnitCost) + $request->cutting_cost ?? 0;
                $receiveCuttingPerUnitCost = $receivedCuttingUnitCost / $request->unit;
            }

            $data = [
                'cutting_id' => $request->cutting_id,
                'total_cost' => $receivedCuttingUnitCost,
                'per_unit_cost' => $receiveCuttingPerUnitCost,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'wastage' => $request->wastage,
                'cutting_cost' => $request->cutting_cost
            ];

            CuttingReceive::create($data);
            $receive = Cutting::findOrFail($request->cutting_id);
            $receive->decrement('available_unit', $request->unit);

            DB::commit();

             return redirect()->back()->with(['status' => true, 'message' => 'Cutting Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
}
