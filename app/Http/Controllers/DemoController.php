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
        // $name = $request['name'];
        // $age = $request['age'];
        // $job = $request['job'];

        // $data = [
        //     'name' => $name,
        //     'age' => $age,
        //     'job' => $job
        // ];

        $product = Product::all();
        $jsonData = json_encode($product, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return response($jsonData, 200)->header('Content-Type', 'application/json');
    }

    public function search(Request $request){
        $apiUrl = 'https://phone5615664.000webhostapp.com/API2';
        $search = $request['search'];
        $response = Http::get($apiUrl, ['search' => $search]);
        if ($response->status() == 200) {
            // Xử lý dữ liệu trả về từ API tại đây
            $data = $response->json();
            dd($data);
        } else {
            return "Yêu cầu không thành công, mã trạng thái: " . $response->status();
        }
    }
}
