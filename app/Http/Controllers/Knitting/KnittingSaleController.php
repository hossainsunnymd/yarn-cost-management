<?php

namespace App\Http\Controllers\Knitting;

use Exception;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\KnittingSale;
use Illuminate\Http\Request;
use App\Models\KnittingReceive;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Knitting\KnittingSaleService;

class KnittingSaleController extends Controller
{
    //knitting sale page
    public function knittingSalePage(Request $request)
    {
        $knittingReceives = KnittingReceive::with('knitting')->get();
        $customers = Customer::all();
        return Inertia::render('Knittings/KnittingSale/KnittingSalePage', ['knittingReceives' => $knittingReceives, 'customers' => $customers]);
    }

    //create knitting sale
    public function createKnittingSale(KnittingSaleService $knittingSaleService, Request $request)
    {
      
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
