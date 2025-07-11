<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //customer list
    public function customerList(){
        $customers=Customer::all();
        return Inertia::render('Customer/CustomerListPage',['customers'=>$customers]);
    }

    //customer save page
    public function customerSavePage(Request $request){

        $customer=Customer::find($request->customer_id);
        return Inertia::render('Customer/CustomerSavePage',['customer'=>$customer]);
    }

    //create customer
    public function createCustomer(Request $request){

        $validation=Validator::make($request->all(),[
            'name'=>'required',
            'phone'=>'required',
            'address'=>'required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with(['error'=>$validation->errors()]);
        }else{
            $data=[
                'name'=>$request->name,
                'phone'=>$request->phone,
                'address'=>$request->address,
            ];
            Customer::create($data);
            return redirect()->back()->with(['status'=>true,'message'=>'Customer Created Successfully','error'=>'']);

        }
    }

    //update customer

    public function updateCustomer(Request $request){

        $validation=Validator::make($request->all(),[
            'name'=>'required',
            'phone'=>'required',
            'address'=>'required',
        ]);

        if($validation->fails()){
            return redirect()->back()->with(['errors'=>$validation->errors()]);
        }else{
            $data=[
                'name'=>$request->name,
                'phone'=>$request->phone,
                'address'=>$request->address,
            ];
            Customer::where('id',$request->customer_id)->update($data);
            return redirect()->back()->with(['status'=>true,'message'=>'Customer Updated Successfully','error'=>'']);

        }
    }

    //delete customer
    public function deleteCustomer(Request $request){
        Customer::find($request->customer_id)->delete();
        return redirect()->back()->with(['status'=>true,'message'=>'Customer Deleted Successfully','error'=>'']);
    }
}


