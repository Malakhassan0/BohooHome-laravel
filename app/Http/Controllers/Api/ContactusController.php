<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contactus;
use Illuminate\Http\Request;

class ContactusController extends Controller
{
    //
    function store(Request $req)
    {
        $data = $req->all();
        $validator = \Validator::make($req->all(), [
            'fname' => 'required|min:2|max:15',
            'lname' => 'required|min:2|max:15',
            'email' => 'required|email|max:100|unique:users,email',
            'message' => 'required|min:5|max:150'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'validation error', 'validation_errors' => $validator->messages()]);
        } else {
            $newContact = Contactus::create($data);
            return response()->json(['message' => 'successfully added', 'status' => 200, 'contact' => $newContact]);
        }
    }

}