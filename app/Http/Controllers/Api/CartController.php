<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Auth;

class CartController extends Controller
{
    //
    function AllCartItems($id)
    {
        $cart = Cart::where('user_id',$id)->get();
        if ($cart) {
            $data=Cart::where('user_id',$id)->where('checked', '=','0')->with('product')->get();
            return response()->json(['message' => 'success', 'status' => 200, 'cart_items'=>$data]);
        } else {
            return response()->json(['message' => 'error user not found']);
        }
    }
    
    // if ($data) {
    //     // $idu = $data['user_id'] ;
    //     $da = Cart::whereuserId($id)->where("checked", 0)->get();
    //     return response()->json(['message' => 'success', 'status' => 200, 'cart_items'=>$da ]);
    // } else {
    //     return response()->json(['message' => 'error user not found']);
    // }

    function storeitems(request $req)
    {
        $data = $req->all();
        // $data['user_id'] = Auth::user()->id;
            $productCheck= Product::where('id',$data['product_id'])->first();
            if($productCheck){
                if(Cart::where('product_id',$data['product_id'])->where('user_id',$data['user_id'])->exists()){
                    return response()->json(['message' => $productCheck->name.'is already added to cart','status' => 409]);
                }else{
                    $newCart = Cart::create($data);
                    return response()->json(['message' => 'the cart is created successfully','status' => 200, 'carts' => $newCart]);
                }
            }else{
                return response()->json(['message' => 'Product Not Found','status'=>404]);
            }
        
    }

    function update(Request $req)
    {
        $data = $req->all();
        Cart::where('quantity');
    }

    function updatequantity($cart_id,$scope,$id){
        $cart = Cart::where('user_id',$id)->get();
        if($cart){
            $cart_item= Cart::where('id',$cart_id)->where('user_id',$id)->where('checked', '=','0')->first();
            if($scope=='inc'){
                $cart_item->quantity+=1;
            }else if($scope=='dec'){
                $cart_item->quantity-=1;
            }
            $cart_item->update();
            return response()->json(['message' => 'Quantity updated', 'status' => 200]);
        }else{
            return response()->json(['message' => 'cart is deleted successfully', 'status' => 401]);
        }
    }
    function deleteCart($cart_id,$id)
    {
        // $id = Auth::user()->id;
        $cart = Cart::where('user_id',$id)->get();
        if($cart){
        $dat=Cart::where('id',$cart_id)->where('user_id',$id)->where('checked', '=','0')->delete();
        return response()->json(['message' => 'cart is deleted successfully', 'status' => 200,$dat]);
    }
        else{
            return response()->json(['message' => 'Login to continue']);
        }
    }
}