<?php

namespace App\Http\Controllers\Dyeing;

use Exception;
use Inertia\Inertia;
use App\Models\Dyeing;
use App\Models\DyeingParty;
use Illuminate\Http\Request;
use App\Models\KnittingReceive;
use App\Http\Controllers\Controller;
use App\Services\Dyeing\DyeingService;
use Illuminate\Support\Facades\Validator;
use App\Services\Dyeing\DyeingReceiveService;

class DyeingController extends Controller
{

    //dyeing list
    public function dyeingList()
    {

        $dyeingList = Dyeing::with('dyeingParty')->get();
        return Inertia::render('Dyeings/Dyeing/DyeingListPage', ['dyeingList' => $dyeingList]);
    }

    //dyeing save page
    public function dyeingSavePage(Request $request)
    {
        $dyeingPartyList = DyeingParty::all();
        $knittingReceive=KnittingReceive::find($request->knitting_receive_id);
        return Inertia::render('Dyeings/Dyeing/DyeingSavePage', ['dyeingPartyList' => $dyeingPartyList,'knittingReceive'=>$knittingReceive]);
    }

    //create dyeing
    public function createDyeing(DyeingService $dyeingService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dyeing_party_id' => 'required',
            'unit' => 'required|numeric',
            'color' => 'required',
            'roll' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()]);
        }

        try {
            $dyeingService->createDyeing($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }
    //dyeing receive page
    public function dyeingReceivePage(Request $request)
    {
        $dyeing=Dyeing::find($request->dyeing_id);
        return Inertia::render('Dyeings/Dyeing/DyeingReceivePage', ['dyeing' => $dyeing]);
    }

    //create dyeing receive
    public function createDyeingReceive(DyeingReceiveService $dyeingReceiveService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|numeric',
            'per_unit_dyeing_cost' => 'required|numeric',
            'roll' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->errors()]);
        }

        try {
            $dyeingReceiveService->createDyeingReceive($request);
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }
}
