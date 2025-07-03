<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Sewing;
use Illuminate\Http\Request;
use App\Models\SewingReceive;
use App\Models\CuttingReceive;
use Illuminate\Support\Facades\DB;

class SewingController extends Controller
{
    //Sewing list
    public function sewingList()
    {
        $sewings = Sewing::all();
        return Inertia::render('Sewing/SewingListPage', ['sewings' => $sewings]);
    }

    //Sewing Save page
    public function sewingSavePage()
    {
        return Inertia::render('Sewing/SewingSavePage');
    }

    //Sewing create
    public function createSewing(Request $request)
    {

        DB::beginTransaction();
        try {
            $data = [
                'cutting_receive_id' => $request->cutting_receive_id,
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
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }

    //sewing receive page
    public function sewingReceivePage()
    {
        return Inertia::render('Sewing/SewingReceivePage');
    }

    //create sewing receive
    public function createSewingReceive(Request $request)
    {

        try {
            $data = [
                'sewing_id' => $request->sewing_id,
                'unit' => $request->unit,
                'available_unit' => $request->unit,
                'wastage' => $request->wastage,
            ];

            SewingReceive::create($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Sewing Receive Created Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()]);
        }
    }
}
