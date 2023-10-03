<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class CategoryController extends Controller
{
    public function category(Request $request)
    {
        $search = $request->input('search'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $perPage = 10; // Số lượng sản phẩm trên mỗi trang, bạn có thể thay đổi theo ý muốn.
        $id = $request->input('id');
        Paginator::currentPageResolver(function () use ($request) {
            return $request->input('page');
        });
    
        $categories = Category::query();
        
        if($id){
            $category = Category::where('idcategory', $id)->first();
            if(!$category || $category->isdelete == 1){
                return response()->json(['message' => 'Danh mục không tồn tại hoặc đã bị xóa'], 404, [], JSON_UNESCAPED_UNICODE);
            }
            if($category->isdelete == 0){
                $jsonData = json_encode($category, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return response($jsonData, 200)->header('Content-Type', 'application/json');
            }  
        }
    
        if ($search) {
            $categories->where('namecategory', 'like', '%' . $search . '%')->where('isdelete', 0);
        }
    
        $categories = $categories->where('isdelete', 0)->paginate($perPage);
    
        $paginationData = [
            'current_page' => $categories->currentPage(),
            'per_page' => $categories->perPage(),
            'total' => $categories->total(),
            'last_page' => $categories->lastPage(),
            'next_page_url' => $categories->nextPageUrl(),
            'prev_page_url' => $categories->previousPageUrl(),
        ];
    
        $jsonData = [
            'data' => $categories->items(), // Danh sách sản phẩm
            'pagination' => $paginationData, // Thông tin phân trang
        ];
    
        $jsonData = json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return response($jsonData, 200)->header('Content-Type', 'application/json');
    }

    public function addcategory(Request $request)
    {
        if (!$request->input('namecategory') || !$request->input('imagecategory')) {
            return response()->json(['message' => 'Vui lòng điền đầy đủ thông tin'], 400, [], JSON_UNESCAPED_UNICODE);
        }

        $category = new Category;
        $category->namecategory = $request->input('namecategory');
        $category->isdelete = 0;
        $category->timedelete = null;
        $category->product_count = 0;
        $category->imagecategory = $request->input('imagecategory');
        $category->save();

        return response()->json(['message' => 'Thêm danh mục thành công'], 201, [], JSON_UNESCAPED_UNICODE);
    }


    public function changecategory(Request $request)
    {
        $id = $request->input('id');
        $category = Product::where('idcategory', $id)->where('isdelete', 0)->first();
        if(!$category){
            return response()->json(['message' => 'Danh mục không tồn tại hoặc đã bị xóa'], 404, [], JSON_UNESCAPED_UNICODE);
        }else{
            $data = $request->only(['namecategory', 'imagecategory']);
            if (empty($data)) {
                return response()->json(['message' => 'Không có dữ liệu mới để cập nhật'], 400, [], JSON_UNESCAPED_UNICODE);
            }

            foreach ($data as $field => $value) {
                $category->$field = $value;
            }
            $category->save();
            return response()->json(['message' => 'Cập nhật danh mục thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        } 

    }

    public function deletecategory(Request $request)
    {
        $search = $request->input('id'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $category = Category::where('idcategory', $search)->first();
        
        if(!$category){
            return response()->json(['message' => 'Danh mục không tồn tại'], 404, [], JSON_UNESCAPED_UNICODE);
        }
        if($category->isdelete == 1){
            return response()->json(['message' => 'Danh mục đã bị xóa trước đó'], 400, [], JSON_UNESCAPED_UNICODE);
        }
        if($category->isdelete == 0){
            $category->isdelete = 1;
            $category->timedelete = Carbon::now();
            $category->save();
            $product = Product::where('idcategory', $category->idcategory)->get();
            foreach($product as $pro){
                $pro->isdelete = 1;
                $pro->save();
            }
            return response()->json(['message' => 'Xóa thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}