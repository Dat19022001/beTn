<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // Nhận dữ liệu từ yêu cầu
        $data = $request->json()->all();

        $user = auth()->user();
        // Tạo một đơn hàng
        $order = new Order;
        $order->user_id = $user -> id; // Thay thế bằng cách xác định user_id
        $order->date = Carbon::now()->toDateString(); // Sử dụng ngày hiện tại
        $order->total = 0; // Bạn cần tính toán tổng tiền
        $order->payment_method = $data['payment_method'];
        $order->address = $data['address'];
        $order->shipping_fee = $data['shipping_fee'];
        $order->payment_status = 'pending'; // Trạng thái thanh toán ban đầu
        $order->phone = $data['phone'];
        $order->notes = $data['notes'];
        $order->save();

        // Tạo danh sách các mặt hàng (order items)
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $orderItem = new OrderDetail;
                $orderItem->order_id = $order->id; // Liên kết với đơn hàng
                $orderItem->product_id = $itemData['product_id'];
                $orderItem->product_name = $itemData['product_name'];
                $orderItem->product_price = $itemData['product_price'];
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->total = $itemData['product_price'] * $itemData['quantity'];
                $orderItem->save();
                $order->total += $orderItem->total; // Cập nhật tổng tiền của đơn hàng
            }
        }
        $order -> total = $order->total + $order->shipping_fee;

        $order->save();

        return response()->json(['message' => 'Đơn hàng đã được tạo', 'order_id' => $order], 200);
    }
    public function getOrderWithDetails($id)
    {
        $order = Order::with('orderDetails')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        return response()->json(['order' => $order], 200);
    }
    public function getOrder(){
        
        $order = Order::with('orderDetails')->get();

        if (!$order) { 
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        return response()->json(['order' => $order], 200);
    }
    public function getOrderUser($id){
        
        $order = Order::with('orderDetails')->where('user_id',$id)->get();

        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        return response()->json(['order' => $order], 200);
    }
}
