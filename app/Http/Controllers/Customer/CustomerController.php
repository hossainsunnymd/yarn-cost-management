<?php

namespace App\Http\Controllers\Customer;

use Exception;
use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //customer list
    public function customerList()
    {
        $customers = Customer::all();
        return Inertia::render('Customer/CustomerListPage', ['customers' => $customers]);
    }

    //customer payment list
    public function customerPaymentList(Request $request)
    {
        $dueAmount = Customer::find($request->customer_id)->due_amount;
        $customerPayment = CustomerPayment::where('customer_id', $request->customer_id)->with('customer')->get();
        $latestPayment = CustomerPayment::latest()->first();
        $totalPayment = CustomerPayment::where('customer_id', $request->customer_id)->sum('amount');
        return Inertia::render('Customer/CustomerPaymentListPage', ['customerPayment' => $customerPayment, 'latestPayment' => $latestPayment, 'totalPayment' => $totalPayment, 'dueAmount' => $dueAmount]);
    }

    //customer save page
    public function customerSavePage(Request $request)
    {

        $customer = Customer::find($request->customer_id);
        return Inertia::render('Customer/CustomerSavePage', ['customer' => $customer]);
    }

    //create customer
    public function createCustomer(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',

        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['error' => $validation->errors()]);
        } else {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'due_amount' => 0
            ];
            Customer::create($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Customer Created Successfully', 'error' => '']);
        }
    }

    //update customer

    public function updateCustomer(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        } else {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ];
            Customer::where('id', $request->customer_id)->update($data);
            return redirect()->back()->with(['status' => true, 'message' => 'Customer Updated Successfully', 'error' => '']);
        }
    }

    //save customer payment
    public function saveCustomerPayment(Request $request){

        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['message' => 'Please Enter valid amount']);
        }

        DB::beginTransaction();
        try{

            $data = [
                'customer_id' => $request->customer_id,
                'amount' => $request->amount,
            ];
            CustomerPayment::create($data);
            Customer::find($request->customer_id)->decrement('due_amount', $request->amount);
            DB::commit();
            return redirect()->back()->with(['status' => true, 'message' => 'Customer Payment Successfully', 'error' => '']);
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }

    }

    //delete customer
    public function deleteCustomer(Request $request)
    {

        try {
            Customer::findOrFail($request->customer_id)->delete();
            return redirect()->back()->with(['status' => true, 'message' => 'Customer Deleted Successfully', 'error' => '']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong', 'error' => '']);
        }
    }
}
