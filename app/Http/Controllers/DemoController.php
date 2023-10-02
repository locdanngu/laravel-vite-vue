<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DemoController extends Controller
{
    //
    public function index(){
        return view('index');
    }

    public function index2(Request $request){
        $search = $request->input('search'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $products = Product::query(); // Bắt đầu một truy vấn Eloquent.

        if ($search) {
            $products->where('nameproduct', 'like', '%' . $search . '%');
        }

        $products = $products->get(); // Lấy kết quả của truy vấn.

        $jsonData = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return response($jsonData, 200)->header('Content-Type', 'application/json');
    }
}