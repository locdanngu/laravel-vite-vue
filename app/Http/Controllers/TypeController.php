<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class TypeController extends Controller
{
    public function type(Request $request)
    {
        $search = $request->input('search'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $perPage = 10; // Số lượng sản phẩm trên mỗi trang, bạn có thể thay đổi theo ý muốn.
        $id = $request->input('id');
        Paginator::currentPageResolver(function () use ($request) {
            return $request->input('page');
        });
    
        $types = Type::query();
        
        if($id){
            $type = Type::where('idtype', $id)->first();
            if(!$type){
                return response()->json(['message' => 'Loại hàng không tồn tại hoặc đã bị xóa'], 404, [], JSON_UNESCAPED_UNICODE);
            }
            if($type){
                $jsonData = json_encode($type, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return response($jsonData, 200)->header('Content-Type', 'application/json');
            }  
        }
    
        if ($search) {
            $types->where('nametype', 'like', '%' . $search . '%');
        }
    
        $types = $types->paginate($perPage);
    
        $paginationData = [
            'current_page' => $types->currentPage(),
            'per_page' => $types->perPage(),
            'total' => $types->total(),
            'last_page' => $types->lastPage(),
            'next_page_url' => $types->nextPageUrl(),
            'prev_page_url' => $types->previousPageUrl(),
        ];
    
        $jsonData = [
            'data' => $types->items(), // Danh sách sản phẩm
            'pagination' => $paginationData, // Thông tin phân trang
        ];
    
        $jsonData = json_encode($types, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return response($jsonData, 200)->header('Content-Type', 'application/json');
    }

    public function addtype(Request $request)
    {
        if (!$request->input('nametype')) {
            return response()->json(['message' => 'Vui lòng điền tên loại hàng'], 400, [], JSON_UNESCAPED_UNICODE);
        }

        $type = new Type;
        $type->nametype = $request->input('nametypecategory');
        $type->product_count = 0;
        $type->save();

        return response()->json(['message' => 'Thêm loại hàng thành công'], 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function changetype(Request $request)
    {
        $id = $request->input('idtype');
        $type = Type::where('idtype', $id)->first();
        if(!$type){
            return response()->json(['message' => 'Loại hàng không tồn tại hoặc đã bị xóa'], 404, [], JSON_UNESCAPED_UNICODE);
        }else{
            if (!$request->input('nametype')) {
                return response()->json(['message' => 'Vui lòng điền tên loại hàng'], 400, [], JSON_UNESCAPED_UNICODE);
            }
            $type->nametype = $request->input('nametype'); 
            $type->save();
            return response()->json(['message' => 'Cập nhật loại hàng thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        } 

    }

    public function deletetype(Request $request)
    {
        $search = $request->input('id'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $type = Type::where('idtype', $search)->first();
        if($type->product_count > 0){
            return response()->json(['message' => 'Bạn không thể xóa loại hàng này'], 400, [], JSON_UNESCAPED_UNICODE);
        }
        
        if(!$type){
            return response()->json(['message' => 'Loại hàng không tồn tại'], 404, [], JSON_UNESCAPED_UNICODE);
        }

        $type->delete();
        return response()->json(['message' => 'Xóa thành công'], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
