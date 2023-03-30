<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Auth;

class CategoryController extends Controller
{
    //
    function index()
    {
        $data = Category::get();
        return response()->json(['message' => 'success', 'status' => 200, 'allcategories' => $data]);
    }

    function singleCategory($identifier)
    {
        $cat = Category::find($identifier);
        return response()->json(['message' => 'success', 'status' => 200, 'cateogry' => $cat]);
    }

    function createCategory(request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:3|max:20'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'validation error', 'status' => 422, 'validation_errors' => $validator->messages()]);
        } else {
            $imgHolder = "categories.jpg";
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '_BOHOdotCom' . "." . $image->getClientOriginalExtension();
                $destination = public_path("/uploads");
                $image->move($destination, $name);
                $imgHolder = $name;
            } else {
                $data['image'] = $imgHolder;
                $data['desc'] = $data['desc'] ?? 'Furnish your home from HUB Furniture online store, variety of home furniture ; living room , bedrooms , dining rooms and outdoor furniture all at one place .';
                $exists = Category::where('name', $data['name'])->exists();
                if (!$exists) {
                    $newCate = new Category;
                    $newCate->create($data);
                    return response()->json(['message' => 'data is addeed successfully', 'status_code' => 200, 'category' => $data]);
                } else {
                    return response()->json(['message' => 'category is already exists', 'status' => 430]);
                }
            }
        }
    }

    function editCategory($identifier)
    {
        $data = Category::find($identifier);
        // $data->update($request->all());
        if ($data) {
            return response()->json(['message' => 'successfully', 'status' => 200, 'category' => $data]);
        } else {
            return response()->json(['message' => 'error']);
        }
    }



    function updateCategory(Request $request, $identifier)
    {
        $data = $request->all();
        $cat = Category::find($identifier);
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:3|max:100'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'validation error', 'status' => 422, 'errors' => $validator->messages()]);
        } else {
            if ($cat) {
                $imgHolder = "categories.jpg";
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $name = time() . '_BOHOdotCom' . "." . $image->getClientOriginalExtension();
                    $destination = public_path("/uploads");
                    $image->move($destination, $name);
                    $imgHolder = $name;
                }
                // $data['user_id'] = Auth::user()->id;
                // $data['user_id'] = 2;
                $cat->image = $imgHolder;
                $data['desc'] = $data['desc'] ?? 'Furnish your home from HUB Furniture online store, variety of home furniture ; living room , bedrooms , dining rooms and outdoor furniture all at one place .';
                $cat->update();
                return response()->json(['message' => 'successfully updated', 'status' => 200, 'category' => $data]);
            } else {
                return response()->json(['message' => 'category not found']);
            }


        }

    }

    function deleteCategory($id)
    {
        $data = Category::find($id);
        if ($data) {
            $data->delete();
            return response()->json(['message' => 'category is successfully deleted', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Not Found']);
        }
    }
}