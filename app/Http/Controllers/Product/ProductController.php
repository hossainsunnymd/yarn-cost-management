<?php

namespace App\Http\Controllers\Product;

use Exception;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\SewingReceive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
    //list product
    public function productList()
    {
        $products = SewingReceive::with('sewing.cuttingReceive.cutting.category')->get();
        return Inertia::render('Products/ProductListPage', ['products' => $products]);
    }

    //update product page
    public function updateProductPage(Request $request)
    {

        $product = SewingReceive::find($request->sewing_receive_id);
        return Inertia::render('Products/ProductUpdatePage', ['product' => $product]);
    }

    //update product
    public function updateProduct(Request $request)
    {

        if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);


            if ($validator->fails()) {
                return redirect()->back()->with(['error' => $validator->errors()]);
            }

            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('uploads', $fileName, 'public');

            $oldImage = SewingReceive::find($request->sewing_receive_id)->image;
            Storage::disk('public')->delete('uploads/' . $oldImage);

            SewingReceive::where('id', $request->sewing_receive_id)->update([
                'image' => $fileName
            ]);

            return redirect()->back()->with(['status' => true, 'message' => 'Product Image Updated Successfully', 'error' => '']);
        } else {
            $oldImage = SewingReceive::find($request->sewing_receive_id)->image;
            if ($oldImage != null && $request->image == null) {
                Storage::disk('public')->delete('uploads/' . $oldImage);
                SewingReceive::where('id', $request->sewing_receive_id)->update([
                    'image' => null
                ]);
                return redirect()->back()->with(['status' => true, 'message' => 'Product Image Remove  Successfully', 'error' => '']);
            }
        }
    }
}
