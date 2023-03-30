<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Auth;
use File;

class ProductController extends Controller
{


    function index()
    {
        $all = Product::all();
        if ($all) {

            return response()->json(['message' => 'success', 'status' => 200, 'allproducts' => $all]);
        } else {
            return response()->json(['message' => 'Nothing Found']);
        }
    }
    public function store(request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($request->all(), [
            'name' => "required",
            'description' => 'required|min:5|max:200',
            'quantity' => 'required',
            'price' => 'required',
            // 'image'=>'required',
            'category_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'validation error', 'status' => 422, 'errors' => $validator->messages()]);
        } else {
            $imgHolder = '1.jpg';
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $nameImg = time() . '_BOHOdotCom' . "." . $image->getClientOriginalExtension();
                $destination = public_path("/uploads");
                $image->move($destination, $nameImg);
                $imgHolder = $nameImg;
            }
            // $data['user_id'] = Auth::user()->id;
            $data['image'] = $imgHolder;
            $prod = new Product();
            $prod->create($data);
            return response()->json(['message' => 'data is addeed successfully', 'status' => 200, 'product' => $data]);
        }
    }
    function editProduct($id)
    {
        $data = Product::find($id);
        if ($data) {
            return response()->json(['message' => 'product successfully found', 'status' => 200, 'product' => $data]);
        } else {
            return response()->json(['message' => 'No Product Found']);
        }
    }
    function singleProduct($id)
    {
        $prod = Product::find($id);
        return response()->json(['message' => 'The product is found', 'status' => 200, 'product' => $prod]);
    }

    function update(request $request, $id)
    {
        $updateProd = Product::find($id);
        $validator = \Validator::make($request->all(), [
            'name' => "required",
            'description' => 'required|min:5|max:200',
            'quantity' => 'required',
            'price' => 'required',
            // 'image' => 'required',
            'category_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'validation error', 'status' => 422, 'errors' => $validator->messages()]);
        } else {
            if ($updateProd) {
                $imgHolder = '1.jpg';
                if ($request->hasFile('image')) {
                    $path = $updateProd->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $image = $request->file('image');
                    $nameImg = time() . '_BOHOdotCom' . "." . $image->getClientOriginalExtension();
                    $destination = public_path("/uploads");
                    $image->move($destination, $nameImg);
                    $imgHolder = $nameImg;
                }
                $updateProd->image = $imgHolder;
                // $updateProd['image'] = $imgHolder;
                // $data['user_id'] = Auth::user()->id;
                $updateProd->update();
                return response()->json(['message' => 'The product is updated successfully', 'status' => 200, 'products' => $updateProd]);
            } else {
                return response()->json(['message' => 'The product not found']);
            }
        }
    }

    function delete($id)
    {
        $delProduct = Product::where('id', $id)->delete();
        return response()->json(['message' => 'The product is deleted successfully', 'status' => 200]);
    }
    function search(Request $request)
    {
        $keyword = $request->get('name');
        $results = Product::where('name', "LIKE", "%$keyword%")->get();
        return response()->json(['message' => 'The product is found successfully', 'status' => 200, 'products' => $results]);
    }

    function catProd($catId){
        $category = Category::where('name',$catId)->first();
        if($category){
            $product= Product::where('category_id',$category->id)->get();
            if($product){
                return response()->json(['product_data'=>[
                    'product'=>$product,
                    'category'=>$category,
                ],'status'=>200]);
            }else{
                return response()->json(['message'=>'No products found','status'=>400]);
            }
        }else{
            return response()->json(['message'=>'No such category found']);
        }
    }
}