<?php

namespace App\Http\Controllers\Sewing;

use App\Models\CuttingReceive;
use Exception;
use Inertia\Inertia;
use App\Models\Sewing;
use App\Models\SewingParty;
use Illuminate\Http\Request;
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

        $sewingParty=SewingParty::all();
        return Inertia::render('Sewings/Sewing/SewingSavePage',['sewingParty'=>$sewingParty , 'cuttingReceive' => $cuttingReceive]);
    }

    //Sewing create
    public function createSewing(SewingService $sewingService,Request $request)
    {
        $validation = Validator::make($request->all(), [
            'sewing_party_id' => 'required',
            'unit' => 'required',
        ],[
            'unit.required'=>'Pcs is required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with([ 'error' => $validation->errors()]);

        }
        try {
            $sewingService->createSewing($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
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
            'unit' => 'required',
        ],[
            'unit.required'=>'Pcs is required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with([ 'error' => $validation->errors()]);

        }

        try {
            $sewingReceiveService->createSewingReceive($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }

    }
}
