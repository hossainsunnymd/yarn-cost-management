<?php

namespace App\Http\Controllers\Cutting;

use App\Services\Cutting\CuttingReceiveService;
use Exception;
use Inertia\Inertia;
use App\Models\Cutting;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\Cutting\CuttingService;

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
    public function createCutting(CuttingService $cuttingService, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_id' => 'required',
            'unit' => 'required|numeric|min:1',
            'roll' => 'required'
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
    public function createCuttingReceive(CuttingReceiveService $cuttingReceiveService, Request $request)
    {
        $validation = Validator::make($request->all(), [
            'cutting_cost' => 'required',
            'unit' => 'required',
        ], [
            'unit.required' => 'Pcs is required',
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
}
