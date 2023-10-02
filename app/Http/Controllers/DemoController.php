<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class DemoController extends Controller
{
    //
    public function index(){
        return view('index');
    }

    public function testapi(Request $request)
    {
        $search = $request->input('search'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $perPage = 10; // Số lượng sản phẩm trên mỗi trang, bạn có thể thay đổi theo ý muốn.
        $id = $request->input('id');
        Paginator::currentPageResolver(function () use ($request) {
            return $request->input('page');
        });
    
        $products = Product::query();
        
        if($id){
            $products->where('idproduct', $id)->where('isdelete', 0);
            $products = $products->get();
            $jsonData = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return response($jsonData, 200)->header('Content-Type', 'application/json');
        }
    
        if ($search) {
            $products->where('nameproduct', 'like', '%' . $search . '%')->where('isdelete', 0);
        }
    
        $products = $products->where('isdelete', 0)->paginate($perPage);
    
        $paginationData = [
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
            'next_page_url' => $products->nextPageUrl(),
            'prev_page_url' => $products->previousPageUrl(),
        ];
    
        $jsonData = [
            'data' => $products->items(), // Danh sách sản phẩm
            'pagination' => $paginationData, // Thông tin phân trang
        ];
    
        $jsonData = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return response($jsonData, 200)->header('Content-Type', 'application/json');
    }

    public function deletetestapi(Request $request)
    {
        $search = $request->input('id'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $product = Product::where('idproduct', $search)->first();
        
        
        if(!$product){
            // return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404, [], JSON_UNESCAPED_UNICODE);

        }
        if($product->isdelete == 1){
            return response()->json(['message' => 'Sản phẩm đã bị xóa trước đó'], 400, [], JSON_UNESCAPED_UNICODE);
        }
        if($product->isdelete == 0){
            $product->isdelete = 1;
            $product->timedelete = Carbon::now();
            $product->save();
            return response()->json(['message' => 'Xóa thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}