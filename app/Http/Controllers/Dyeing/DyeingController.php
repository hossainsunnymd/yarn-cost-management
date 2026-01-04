<?php

namespace App\Http\Controllers\Dyeing;

use Exception;
use Inertia\Inertia;
use App\Models\Dyeing;
use App\Models\DyeingParty;
use Illuminate\Http\Request;
use App\Models\DyeingReceive;
use App\Models\KnittingReceive;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DyeingPayment;
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
        $knittingReceive = KnittingReceive::find($request->knitting_receive_id);
        return Inertia::render('Dyeings/Dyeing/DyeingSavePage', ['dyeingPartyList' => $dyeingPartyList, 'knittingReceive' => $knittingReceive]);
    }

    //create dyeing
    public function createDyeing(DyeingService $dyeingService, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'dyeing_party_id' => 'required|exists:dyeing_parties,id',
            'challan_no' => 'required|integer|unique:dyeings,challan_no',
            'unit' => 'required|numeric|min:1',
            'color' => 'required',
            'roll' => 'required|numeric|min:1',
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

    //delete dyeing
    public function dyeingDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $dyeing = Dyeing::find($request->dyeing_id);
            $knittingReceive = KnittingReceive::where('id', $dyeing->knitting_receive_id)->first();
            $knittingReceive->increment('available_unit', $dyeing->available_unit);
            $knittingReceive->increment('roll', $dyeing->roll);
            $dyeing->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }



    //dyeing receive page
    public function dyeingReceivePage(Request $request)
    {
        $dyeing = Dyeing::find($request->dyeing_id);
        return Inertia::render('Dyeings/Dyeing/DyeingReceivePage', ['dyeing' => $dyeing]);
    }

    //create dyeing receive
    public function createDyeingReceive(DyeingReceiveService $dyeingReceiveService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|numeric|min:1',
            'per_unit_dyeing_cost' => 'required|numeric|min:1',
            'roll' => 'required|numeric|min:1',
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

    //delete dyeing receive
    public function dyeingReceiveDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $dyeingReceive = DyeingReceive::findOrFail($request->dyeing_receive_id);
            $dyeing = Dyeing::find($dyeingReceive->dyeing_id);
            $dyeing->increment('available_unit', $dyeingReceive->unit + $dyeingReceive->wastage??0);
            $dyeing->increment('roll', $dyeingReceive->roll);
            DyeingPayment::where('challan_no', $dyeingReceive->challan_no)->delete();
            $dyeingReceive->delete();
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Dyeing Receive Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => '']);
        }
    }
}
