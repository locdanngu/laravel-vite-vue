<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    //
    public function index(){
        return view('index');
    }

    public function index2(Request $request){
        // $name = $request['name'];
        // $age = $request['age'];
        // $job = $request['job'];

        // $data = [
        //     'name' => $name,
        //     'age' => $age,
        //     'job' => $job
        // ];

        $product = Product::all();
        return response()->json($product,200);
    }
}
