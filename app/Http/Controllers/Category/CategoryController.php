<?php

namespace App\Http\Controllers\Category;

use Exception;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
      //list category
    public function listCategory()
    {
        $categories = Category::all();
        return Inertia::render('Category/CategoryListPage', ['categories' => $categories]);
    }

    //category save page
    public function categorySavePage(Request $request)
    {
        $categoryId = $request->category_id;
        $category = Category::where('id', $categoryId)->first();
        return Inertia::render('Category/CategorySavePage', ['category' => $category]);
    }

        //create category
    public function createCategory(Request $request){

        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with(['error' => $validator->errors()]);
        }

      try{
         Category::create([
            'name'=>$request->category_name
        ]);
        return redirect()->back()->with(['status'=>true,'message'=>'Category created successfully']);
      }catch(Exception $e){
        return redirect()->back()->with(['status'=>false,'message'=>'somethintg went wrong']);
      }
    }

    //update category
    public function updateCategory(Request $request){

          $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with(['errors' => $validator->errors()]);
        }
       try{
        Category::where('id',$request->category_id)->update([
            'name'=>$request->category_name
        ]);
        return redirect()->back()->with(['status'=>true,'message'=>'Category updated successfully']);
       }catch(Exception $e){
        return redirect()->back()->with(['status'=>false,'message'=>'somethintg went wrong']);
       }
    }

    //delete category
    public function deleteCategory(Request $request){
       try{
         Category::where('id',$request->category_id)->delete();
        return redirect()->back()->with(['status'=>true,'message'=>'Category deleted successfully']);
       }catch(Exception $e){
        return redirect()->back()->with(['status'=>false,'message'=>'somethintg went wrong']);
       }
    }
}
