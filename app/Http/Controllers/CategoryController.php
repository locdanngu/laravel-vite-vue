<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
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

        $countcate = Category::all();
        foreach($countcate as $cc){
            $cc->product_count = Product::where('idcategory', $cc->idcategory)->where('isdelete', 0)->count();
            $cc->save();
        }
        $counttype = Type::all();
        foreach($counttype as $ct){
            $ct->product_count = Product::where('idtype', $ct->idtype)->where('isdelete', 0)->count();
            $ct->save();
        }
    
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
        if (!$request->input('namecategory')) {
            return response()->json(['message' => 'Vui lòng điền tên danh mục'], 400, [], JSON_UNESCAPED_UNICODE);
        }
        if (!$request->file('imagecategory')) {
            return response()->json(['message' => 'Vui lòng đính kèm file'], 400, [], JSON_UNESCAPED_UNICODE);
        }

        $category = new Category;
        $category->namecategory = $request->input('namecategory');
        $category->isdelete = 0;
        $category->timedelete = null;
        $category->product_count = 0;
        if ($request->hasFile('imagecategory')) {
            $image = $request->file('imagecategory');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('image/category/');
            $image->move($path, $filename);
            $category->imagecategory = '/image/category/' . $filename;
        }
        $category->save();

        return response()->json(['message' => 'Thêm danh mục thành công'], 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function changecategory(Request $request)
    {
        $id = $request->input('idcategory');
        $category = Category::where('idcategory', $id)->where('isdelete', 0)->first();
        if(!$category){
            return response()->json(['message' => 'Danh mục không tồn tại hoặc đã bị xóa'], 404, [], JSON_UNESCAPED_UNICODE);
        }else{
            if (!$request->input('namecategory')) {
                return response()->json(['message' => 'Vui lòng điền tên danh mục'], 400, [], JSON_UNESCAPED_UNICODE);
            }
            $category->namecategory = $request->input('namecategory'); 
            if ($request->hasFile('imagecategory')) {
                $image = $request->file('imagecategory');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('image/category/');
                $image->move($path, $filename);
                $category->imagecategory = '/image/category/' . $filename;
            }

            $category->save();
            return response()->json(['message' => 'Cập nhật danh mục thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        } 

    }

    public function deletecategory(Request $request)
    {
        $search = $request->input('id'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $category = Category::where('idcategory', $search)->first();
        if($category->product_count > 0){
            return response()->json(['message' => 'Bạn không thể xóa danh mục này'], 400, [], JSON_UNESCAPED_UNICODE);
        }
        
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
            return response()->json(['message' => 'Xóa thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}