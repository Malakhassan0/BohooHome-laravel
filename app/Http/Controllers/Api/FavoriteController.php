<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Auth;

class FavoriteController extends Controller
{
    //
    function addtofavorites(Request $req)
    {
        $data = $req->all();
        if ($data) {
            // $data['user_id'] = Auth::user()->id;
            $fav = Favorite::create($data);           
            return response()->json(['message' => 'product is added to wishlist successfully', 'status_code' => 200, 'Favorite_list' => $fav]);
        } else {
            return response()->json(['message' => 'error user not found']);
        }
    }
    function getFavoriteList($id)
    {
       
        $data = Favorite::where('user_id', $id)->get();
        if ($data) {
            // $id = Auth::user()->id();
            $list= Favorite::where('user_id',$id)->with('product')->get();
            return response()->json(['message' => 'success', 'status_code' => 200, 'Favorite_list' => $list]);
        } else {
            return response()->json(['message' => 'error user not found']);
        }
    }

    function deleteFavorite(Request $req, $id)
    {
        $data= $req->all();
        if ($data) {
            // $idu = Auth::user()->id();
            // ->where('user_id', $idu)
            // $idu = 3;
            Favorite::where('product_id', $id)->where('user_id',$data['user_id'])->delete();
            return response()->json(['message' => 'successfully deleted', 'status_code' => 200]);
        } else {
            return response()->json(['message' => 'error user not found']);
        }
    }
}
