<?php

namespace App\Http\Controllers\Sewing;

use Exception;
use Inertia\Inertia;
use App\Models\Sewing;
use App\Models\SewingParty;
use Illuminate\Http\Request;
use App\Models\SewingReceive;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class SewingController extends Controller
{
    //Sewing list
    public function sewingList()
    {
        $sewings = Sewing::with('cuttingReceive.cutting.category', 'sewingParty')->get();
        return Inertia::render('Sewings/Sewing/SewingListPage', ['sewings' => $sewings]);
    }

    //Sewing Save page
    public function sewingSavePage()
    {
        $sewingParty=SewingParty::all();
        return Inertia::render('Sewings/Sewing/SewingSavePage',['sewingParty'=>$sewingParty]);
    }

    //Sewing create
    public function createSewing(Request $request)
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

        //check available unit
        $cutting = CuttingReceive::findOrFail($request->cutting_receive_id);
        if ($cutting->available_unit < $request->unit) {
            return redirect()->back()->with(['status' => false, 'message' => 'Unit Not Available', 'error' => '']);
        }


        DB::beginTransaction();
        try {
            $data = [
                'cutting_receive_id' => $request->cutting_receive_id,
                'sewing_party_id' => $request->sewing_party_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
            ];

            Sewing::create($data);
            $cutting = CuttingReceive::findOrFail($request->cutting_receive_id);
            $cutting->decrement('available_unit', $request->unit);

            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }

    //sewing receive page
    public function sewingReceivePage()
    {
        return Inertia::render('Sewings/Sewing/SewingReceivePage');
    }

    //create sewing receive
    public function createSewingReceive(Request $request)
    {
        // return $request->all();
        $validation = Validator::make($request->all(), [
            'sewing_cost' => 'required',
            'unit' => 'required',
        ],[
            'unit.required'=>'Pcs is required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with([ 'error' => $validation->errors()]);

        }

        //check available unit
        $sewing = Sewing::findOrFail($request->sewing_id);
        if ($sewing->available_unit < $request->unit) {
            return redirect()->back()->with(['status' => false, 'message' => 'Unit Not Available', 'error' => '']);
        }

        DB::beginTransaction();
        try {
            $sewing = Sewing::findOrFail($request->sewing_id);
            $perUnitCost = CuttingReceive::find($sewing->cutting_receive_id)->per_unit_cost;

            //calulate received sewing unit cost
            $receivedSewingUnitCost = ($request->unit * $perUnitCost) + $request->sewing_cost ?? 0;
            $receiveSewingPerUnitCost = $receivedSewingUnitCost / $request->unit;


            if ($request->wastage > 0) {

                $receivedSewingUnitCost = (($request->unit + $request->wastage) * $perUnitCost) + $request->sewing_cost ?? 0;
                $receiveSewingPerUnitCost = $receivedSewingUnitCost / $request->unit;
            }

            $data = [
                'sewing_id' => $request->sewing_id,
                'total_cost' => $receivedSewingUnitCost,
                'per_unit_cost' => $receiveSewingPerUnitCost,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'wastage' => $request->wastage,
                'sewing_cost' => $request->sewing_cost
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('uploads', $fileName);
                $data['image'] = $fileName;
            }


            SewingReceive::create($data);
            $receive = Sewing::findOrFail($request->sewing_id);
            $receive->decrement('available_unit', $request->unit);

            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' =>'something went wrong', 'error' => '']);
        }
    }
}
