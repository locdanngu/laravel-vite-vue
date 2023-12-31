<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class ProductController extends Controller
{

    public function product(Request $request)
    {
        $search = $request->input('search'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $perPage = 10; // Số lượng sản phẩm trên mỗi trang, bạn có thể thay đổi theo ý muốn.
        $id = $request->input('id');
        Paginator::currentPageResolver(function () use ($request) {
            return $request->input('page');
        });
    
        $products = Product::query();
        
        if($id){
            $product = Product::where('idproduct', $id)->first();
            if(!$product || $product->isdelete == 1){
                return response()->json(['message' => 'Sản phẩm không tồn tại hoặc đã bị xóa'], 404, [], JSON_UNESCAPED_UNICODE);
            }
            if($product->isdelete == 0){
                $idcate = $product->idcategory;
                $idtype = $product->idtype;
                $product->namecategory = Category::where('idcategory', $idcate)->first();
                $product->nametype = Type::where('idtype', $idtype)->first();
            
                // Xóa idcategory và idtype nếu bạn muốn
                unset($product->idcategory);
                unset($product->idtype);
                $jsonData = json_encode($product, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return response($jsonData, 200)->header('Content-Type', 'application/json');
            }
        }
    
        if ($search) {
            $products->where('nameproduct', 'like', '%' . $search . '%')->where('isdelete', 0);
        }
    
        $products = $products->where('isdelete', 0)->paginate($perPage);
        
        
        foreach ($products as $product) {
            // Lấy namecategory và nametype tương ứng với idcategory và idtype
            $idcate = $product->idcategory;
            $idtype = $product->idtype;
            $product->namecategory = Category::where('idcategory', $idcate)->first();
            $product->nametype = Type::where('idtype', $idtype)->first();
            
            // Xóa idcategory và idtype nếu bạn muốn
            unset($product->idcategory);
            unset($product->idtype);
        }
        
    
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

    public function addproduct(Request $request)
    {
        if (!$request->input('nameproduct') || !$request->input('price') || !$request->input('detail')  || !$request->input('idcategory') || !$request->input('idtype')) {
            return response()->json(['message' => 'Vui lòng điền đầy đủ thông tin'], 400, [], JSON_UNESCAPED_UNICODE);
        }

        if (!$request->file('imageproduct')) {
            return response()->json(['message' => 'Vui lòng đính kèm file'], 400, [], JSON_UNESCAPED_UNICODE);
        }

        $product = new Product;
        $product->nameproduct = $request->input('nameproduct');
        $product->oldprice = $request->input('oldprice');
        $product->price = $request->input('price');
        $product->detail = $request->input('detail');
        $product->isdelete = 0;
        $product->idcategory = $request->input('idcategory');
        $product->idtype = $request->input('idtype');
        $product->sold = 0;
        if ($request->hasFile('imageproduct')) {
            $image = $request->file('imageproduct');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('image/product/');
            $image->move($path, $filename);
            $product->imageproduct = '/image/product/' . $filename;
        }
        $product->save();

        $category = Category::where('idcategory', $request->input('idcategory'))->first();
        $category->product_count = $category->product_count + 1;
        $category->save();

        $type = Type::where('idtype', $request->input('idtype'))->first();
        $type->product_count = $type->product_count + 1;
        $type->save();

        return response()->json(['message' => 'Thêm sản phẩm thành công'], 201, [], JSON_UNESCAPED_UNICODE);
    }


    public function changeproduct(Request $request)
    {
        $id = $request->input('idproduct');
        $product = Product::where('idproduct', $id)->where('isdelete', 0)->first();
        if(!$product){
            return response()->json(['message' => 'Sản phẩm không tồn tại hoặc đã bị xóa'], 404, [], JSON_UNESCAPED_UNICODE);
        }else{
            if($product->idcategory != $request->input('idcategory')){
                $category2 = Category::where('idcategory', $request->input('idcategory'))->first();
                if(!$category2){
                    return response()->json(['message' => 'Danh mục mới không tồn tại trong hệ thống'], 400, [], JSON_UNESCAPED_UNICODE);
                }
                $category = Category::where('idcategory', $product->idcategory)->first();
                $category->product_count = $category->product_count - 1;
                $category->save();
                $category2->product_count = $category2->product_count + 1;
                $category2->save();
            }

            if($product->idtype != $request->input('idtype')){
                $type2 = Type::where('idtype', $request->input('idtype'))->first();
                if(!$type2){
                    return response()->json(['message' => 'Loại hàng mới không tồn tại trong hệ thống'], 400, [], JSON_UNESCAPED_UNICODE);
                }
                $type = Type::where('idtype', $product->idtype)->first();
                $type->product_count = $type->product_count - 1;
                $type->save();
                $type2->product_count = $type2->product_count + 1;
                $type2->save();
            }

            $product->nameproduct = $request->input('nameproduct');
            $product->oldprice = $request->input('oldprice');
            $product->price = $request->input('price');
            $product->detail = $request->input('detail');
            $product->idcategory = $request->input('idcategory');
            $product->idtype = $request->input('idtype');
            if ($request->hasFile('imageproduct')) {
                $image = $request->file('imageproduct');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('image/product/');
                $image->move($path, $filename);
                $product->imageproduct = '/image/product/' . $filename;
            }
            $product->save();

            return response()->json(['message' => 'Cập nhật sản phẩm thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        } 

    }

    public function deleteproduct(Request $request)
    {
        $search = $request->input('id'); // Sử dụng $request->input() để lấy giá trị của tham số 'search'.
        $product = Product::where('idproduct', $search)->first();
        
        if(!$product){
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404, [], JSON_UNESCAPED_UNICODE);
        }

        if($product->isdelete == 0){
            $product->isdelete = 1;
            $product->timedelete = Carbon::now();
            $product->save();
            $category = Category::where('idcategory', $product->idcategory)->first();
            $category->product_count = $category->product_count - 1;
            $category->save();
            $type = Type::where('idtype', $product->idtype)->first();
            $type->product_count = $type->product_count - 1;
            $type->save();

            return response()->json(['message' => 'Xóa thành công'], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}