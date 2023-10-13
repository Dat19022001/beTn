<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // thêm sản phẩm
    public function addProduct(Request $request)
    {
        $data = $request->json()->all();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'image' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $product = Product::create([
                "name" => $data["name"],
                "image" => $data['image'],
                "description" => $data['description'],
                "price" => $data['price'],
                "category_id" => $data['category_id'],
                "producer" => $data['producer'],
            ]);
            return response()->json(['message' => 'Add product successfully', 'product' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'add fail product'], 400);
        }
    }
    //lấy thông tin sản phẩm có số trang 
    public function getProduct(Request $request)
    {
        $perPage = 2; // Số sản phẩm trên mỗi trang (mặc định là 10)
        $data = $request->json()->all();

        $page = $data['page']; // Số trang muốn lấy (mặc định là 1)
        try {
            $products = Product::paginate($perPage, ['*'], 'page', $page);
            $transformedProducts = $products->getCollection()->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'description' => $product->description,
                    'price' => $product->price,
                    'category_name' => $product->category->name,
                    'producer' => $product->producer,
                ];
            });
            return response()->json(['products' => $transformedProducts], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Get fail product'], 400);
        }
    }
    //lấy thông tin một sản phẩm
    public function getDetailProduct($id)
    {
        $product = Product::find($id);
        $product1 = [
            'id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'description' => $product->description,
            'price' => $product->price,
            'category_name' => $product->category->name, // Lấy tên danh mục
            'producer' => $product->producer
        ];
        if ($product) {
            return response()->json(['product' => $product1], 200);
        }
        return response()->json(["mess" => "san pham khong ton tai"]);
    }
    //xóa một sản phẩm
    public function deleteProduct($id)
    {
        // tìm kiếm các giỏ hàng đang có sản phẩm cần xóa
        $cartItemsToDelete = CartItem::where('product_id', $id)->get();
        // chạy tất cả các giỏ hàng có sản phẩm cần xóa và xóa
        foreach ($cartItemsToDelete as $cartItem) {
            $cartItem->delete();
        }
        // tìm kiếm sản phẩm cần xóa theo id 
        $product = Product::find($id);
        if ($product) {
            // Nếu sản phẩm tồn tại, thực hiện xóa
            $product->delete();
            return response()->json(['message' => 'Sản phẩm đã được xóa thành công'], 200);
        } else {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }
    }
    //chỉnh sửa thông tin sản phẩm 
    public function updateProduct(Request $request, $id)
    {
        // chuyển từ json sang mảng
        $data = $request->json()->all();
        // tìm kiếm sản phẩm có id truyền vào
        $product = Product::find($id);
        //kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json(['message' => "sản phẩm không tồn tại"], 404);
        }
        // gọi hàm update
        $product->update([
            "name" => $data["name"],
            "image" => $data['image'],
            "description" => $data['description'],
            "price" => $data['price'],
            "category_id" => $data['category_id'],
            "producer" => $data['producer']
        ]);
        // trả về kết quả
        return response()->json(['message' => 'Sản phẩm đã được cập nhật thành công'], 200);
    }
    // tìm kiếm theo tên sản phẩm
    public function searchProduct(Request $request)
    {
        $data = $request->json()->all();
        $products = Product::where('name', 'like', '%' . $data['search'] . "%")->get();
        if ($products->isEmpty()) {
            return response()->json(["mess" => "Không có sản phẩm nào phù hợp"], 404);
        }
       
        $transformedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'description' => $product->description,
                'price' => $product->price,
                'category_name' => $product->category->name, // Lấy tên danh mục
                'producer' => $product->producer,
            ];
        });
       
        return response()->json(['products' => $transformedProducts], 200);
    }
    public function byCategory(Request $request)
    {
        $data = $request->json()->all();
        $products = Product::where('category_id',$data['category_id'])->get();
        if ($products->isEmpty()) {
            return response()->json(["mess" => "Không có sản phẩm nào phù hợp"], 404);
        }
       
        $transformedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'description' => $product->description,
                'price' => $product->price,
                'category_name' => $product->category->name, // Lấy tên danh mục
                'producer' => $product->producer,
            ];
        });
       
        return response()->json(['products' => $transformedProducts], 200);
    }
}
