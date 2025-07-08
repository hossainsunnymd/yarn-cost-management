<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Cutting;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\DyeingReceive;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CuttingController extends Controller
{

    //list cutting
    public function cuttingList()
    {
        $cuttings = Cutting::with('category')->get();
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

        $validation=Validator::make($request->all(),[
            'category_id'=>'required',
            'unit'=>'required',
            'roll'=>'required'
        ]);

        if($validation->fails()){
            return redirect()->back()->with([ 'error' => $validation->errors()]);
        }

        //check available unit
        $cutting = DyeingReceive::findOrFail($request->dyeing_receive_id);
        if ($cutting->available_unit < $request->unit) {
            return redirect()->back()->with(['status' => false, 'message' => 'Unit Not Available', 'error' => '']);
        }

        try {
            DB::beginTransaction();

            $data = [
                'dyeing_receive_id' => $request->dyeing_receive_id,
                'category_id' => $request->category_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'roll' => $request->roll
            ];

            Cutting::create($data);

            $cutting = DyeingReceive::findOrFail($request->dyeing_receive_id);
            $cutting->decrement('available_unit', $request->unit);

            DB::commit();

            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' =>  $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }

    //cutting receive list
    public function cuttingReceiveList()
    {
        $cuttingReceives = CuttingReceive::with('cutting.category')->get();
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
        $validation=Validator::make($request->all(),[
            'cutting_cost'=>'required',
            'unit'=>'required',
        ],[
            'unit.required'=>'Pcs is required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with([ 'error' => $validation->errors()]);
        }

        DB::beginTransaction();
        try {
            $cutting = Cutting::findOrFail($request->cutting_id);
            $totalUnit=$cutting->unit;
            $perUnitCost = DyeingReceive::find($cutting->dyeing_receive_id)->per_unit_cost;

            //calculate total unit cost
            $totalUnitCost = ($totalUnit * $perUnitCost) + $request->cutting_cost ?? 0;


            //calculate per unit cost
            $perPcCost = $totalUnitCost / $request->unit;


            $data = [
                'cutting_id' => $request->cutting_id,
                'total_cost' => $totalUnitCost,
                'per_unit_cost' => $perPcCost,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'cutting_cost' => $request->cutting_cost
            ];

            CuttingReceive::create($data);
            $receive = Cutting::findOrFail($request->cutting_id);
            $receive->decrement('available_unit', $cutting->unit);

            DB::commit();

             return redirect()->back()->with(['status' => true, 'message' => 'Cutting Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
}
