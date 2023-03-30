<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    //
    function createOrder(Request $req){
        $data = $req->all();
        if($data){
            $cart = Cart::where('user_id',$data['user_id'])->with('product')->get();
            // $orderitems=[];
            // $orderitems[]=[];
            
            foreach($cart as $item){
                $newOrder=Order::create([
                    'user_id'=>$data['user_id'],
                    'product_id'=>$item->product_id,
                    'quantity'=>$item->quantity,
                    'status'=>'inprogress',
                    'total'=>$item->product->price*$item->quantity,
                    'payement'=>$data['payement']
                ]);
                $item->product->update([
                    'quantity'=>$item->product->quantity-$item->quantity
                ]);
                // $item->update(['checked'=>'1' ]);
            }
            Cart::destroy($cart);
            return response()->json(['message'=>'order is placed successfully','status'=>200,'order'=>$newOrder]);
        }else{
            return response()->json(['message'=>'something went wrong']);
        }
    }

    function index(){
        $order= Order::with('user')->get();
        return response()->json(['status'=>200,'orders'=>$order]);
    }

    function delete($id)
    {
        $delProduct = Order::where('id', $id)->delete();
        return response()->json(['message' => 'The product is deleted successfully', 'status' => 200]);
    }

    function update(Request $req,$id){
        $updateStatus = Order::find($id);
        if($updateStatus){
            $updateStatus->status=$req->input('status');
            $updateStatus->save();
            return response()->json(['message' => 'The status is updated successfully', 'status' => 200, 'products' => $updateStatus]);
        } else {
            return response()->json(['message' => 'Something went wrong']);
        }
    }
}
