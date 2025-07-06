<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\KnittingSale;
use Illuminate\Http\Request;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\Validator;

class KnittingSaleController extends Controller
{
      //knitting sale page
    public function knittingSalePage(Request $request)
    {
        return Inertia::render('Knittings/KnittingSale/KnittingSalePage');
    }

    //create knitting sale
    public function createKnittingSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }

        $data = [
            'knitting_receive_id' => $request->knitting_receive_id,
            'unit' => $request->unit,
            'total_amount' => $request->total_amount,
        ];
        KnittingSale::create($data);
        KnittingReceive::where('id', $request->knitting_receive_id)->decrement('available_unit', $request->unit);
        return redirect()->back()->with(['status' => true, 'message' => 'Knitting Sale Created Successfully', 'error' => '']);
    }

    //knitting sale list
    public function knittingSaleList()
    {
        $knittingSaleList = KnittingSale::get();
        return Inertia::render('Knittings/KnittingSale/KnittingSaleListPage', ['knittingSaleList' => $knittingSaleList]);
    }


}
