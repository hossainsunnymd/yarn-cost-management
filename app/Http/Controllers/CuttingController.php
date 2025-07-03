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

            $data = [
                'dyeing_receive_id' => $request->dyeing_receive_id,
                'category_id' => $request->category_id,
                'roll' => $request->roll,
            ];

            Cutting::create($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Created Successfully', 'error' => '']);
        } catch (Exception $e) {
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

        try {
            $data = [
                'cutting_id' => $request->cutting_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'wastage' => $request->wastage,
            ];

            CuttingReceive::create($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Cutting Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
}
