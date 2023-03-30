<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class AuthController extends Controller
{
    //
    function handleregister(request $req)
    {
        $validator = Validator::make($req->all(), [
            'fname' => 'required|min:2|max:15',
            'lname' => 'required|min:2|max:15',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'required|min:5|max:15',
            'address' => 'required|min:5|max:100'
        ]);
        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->messages()]);
        } else {
            $data = $req->all();
            $user = new User();
            $data['password'] = \Hash::make($data['password']);
            // $token = $user->createToken($user->email . '_Token')->plainTextToken;
            $data['remember_token'] = \Str::random(64);
            $user = User::create($data);
            return response()->json(['data' => $user, 'token' => $user->remember_token, 'status_code' => 200]);

            // return $user ? redirect()->away(env('http://localhost:3000/login')) : redirect()->away(env('http://localhost:3000/register'));
        }
    }

    function handleLogin(request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->messages()]);
        } else {
            $userCred = Auth::attempt(['email' => $req->email, 'password' => $req->password]);
            // $user = User::where('email', $req->email)->first();
            // $token = $user->createToken($user->email . '_Token')->plainTextToken;
            // return response()->json(['data' => $userCred, 'username' => $user->fname, 'token' => $token, 'message' => 'logged in successfully', 'status_code' => 200]);
            $user = Auth::user();
            if (!$userCred) {
                return response()->json(['message' => 'email or password is invalid']);
            } else {
                return response()->json(['userData' => $user, 'token' => $user->remember_token, 'message' => 'logged in successfully', 'status_code' => 200]);
            }
        }
    }
    function logout()
    {
        $user = Auth::user();
        Auth::logout();
        return response()->json(['message' => 'logged out successfully', $user, 'status' => 200]);

    }
    function user()
    {
        $user = auth()->user();
        if ($user) {
            return response()->json(['message' => 'success', 'status' => 200, "user" => $user]);
        } else {
            return response()->json(['message' => 'not found']);
        }
    }

}