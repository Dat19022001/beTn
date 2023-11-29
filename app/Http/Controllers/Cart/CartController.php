<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class CartController extends Controller
{
    //thêm vào giỏ hàng
    public function addToCart(Request $request)
    {
        // Lấy thông tin sản phẩm từ request
        $data = $request->json()->all();
        $productId = $data['product_id'];
        $quantity = $data['quantity']; // Mặc định số lượng là 1

        // Kiểm tra sản phẩm tồn tại trong cơ sở dữ liệu
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        // Lấy giỏ hàng của người dùng (hoặc tạo mới nếu chưa có)
        $user = auth()->user();
        $cart = $user->cart;

        if (!$cart) {
            $cart = new Cart();
            $user->cart()->save($cart);
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng hay chưa
        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            $cartItem = new CartItem([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            $cart->cartItems()->save($cartItem);
        }

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng'], 200);
    }
    // lấy thông tin giỏ hàng
    public function showCart()
    {
        // Lấy thông tin của người dùng đã xác thực
        $user = auth()->user();


        if (!$user) {
            return response()->json(['message' => 'Người dùng không xác thực'], 401);
        }

        // Lấy giỏ hàng của người dùng nếu có
        $cart = $user->cart;

        if (!$cart) {
            return response()->json(['message' => 'Giỏ hàng của người dùng rỗng'], 200);
        }

        // Lấy danh sách sản phẩm trong giỏ hàng
        // $cartItems = $cart->cartItems;
        // Lấy danh sách sản phẩm trong giỏ hàng kèm theo chi tiết của từng sản phẩm
        $cartItems = $cart->cartItems()->with('product')->get();

        return response()->json(['cart' => $cartItems], 200);
    }
    //tăng giảm số lượng từng sản phẩm
    public function updateCartItem(Request $request, $cartItemId)
    {
        $user = auth()->user();
        $data = $request->json()->all();

        if (!$user) {
            return response()->json(['message' => 'Người dùng không xác thực'], 401);
        }

        // Tìm mục trong giỏ hàng của người dùng dựa trên cartItemId
        $cartItem = $user->cart->cartItems()->find($cartItemId);

        if (!$cartItem) {
            return response()->json(['message' => 'Mục trong giỏ hàng không tồn tại'], 404);
        }

        // Cập nhật số lượng sản phẩm trong giỏ hàng
        $type = $data['type'];
        if ($type === "plus") {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity -= 1;
                $cartItem->save();
            } else {
                $cartItem->quantity = 1;
                $cartItem->save();
            }
        }

        return response()->json(['message' => 'Số lượng sản phẩm trong giỏ hàng đã được cập nhật', "data" =>  $cartItem], 200);
    }
    //xóa một sản phẩm
    public function removeCart($cartItemId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Người dùng không xác thực'], 401);
        }

        $cartItem = $user->cart->cartItems()->find($cartItemId);

        if (!$cartItem) {
            return response()->json(['message' => 'Mục trong giỏ hàng không tồn tại'], 404);
        }

        // Xóa sản phẩm khỏi giỏ hàng
        $cartItem->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng'], 200);
    }
    // xóa nhiều sản phẩm
    public function removeCarts(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Người dùng không xác thực'], 401);
        }
        $data = $request->json()->all();
        $productIds = $data['dsId'];


        if (!empty($productIds)) {
            // Xóa nhiều sản phẩm từ giỏ hàng
            // $user->cart->cartItems()->whereIn('product_id', $productIds)->delete();
            $dsProduct = $user->cart->cartItems()->whereIn('product_id', $productIds)->get()->toArray();
            if (!empty($dsProduct)) {
                $user->cart->CartItems()->whereIn('product_id', $productIds)->delete();
            } else {
                return response()->json(['message' => 'Xóa thất bại'], 200);
            }
        } else {
            return response()->json(['message' => 'Xóa thất bại'], 200);
        }

        return response()->json(['message' => 'Các sản phẩm đã được xóa khỏi giỏ hàng'], 200);
    }
}
