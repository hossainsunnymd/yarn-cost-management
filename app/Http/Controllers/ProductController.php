<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\SewingReceive;


class ProductController extends Controller
{
    //list product
    public function productList(){
        $products=SewingReceive::all();
        return Inertia::render('Products/ProductListPage', ['products' => $products]);
    }
}
