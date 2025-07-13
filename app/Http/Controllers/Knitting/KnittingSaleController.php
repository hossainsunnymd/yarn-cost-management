<?php

namespace App\Http\Controllers\Knitting;

use Exception;
use Inertia\Inertia;
use App\Models\KnittingSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\Knitting\KnittingSaleService;

class KnittingSaleController extends Controller
{
    //knitting sale page
    public function knittingSalePage(Request $request)
    {
        return Inertia::render('Knittings/KnittingSale/KnittingSalePage');
    }

    //create knitting sale
    public function createKnittingSale(KnittingSaleService $knittingSaleService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()]);
        }

        try {
            $knittingSaleService->createKnittingSale($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Knitting sale created successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }

    //knitting sale list
    public function knittingSaleList()
    {
        $knittingSaleList = KnittingSale::get();
        return Inertia::render('Knittings/KnittingSale/KnittingSaleListPage', ['knittingSaleList' => $knittingSaleList]);
    }
}
