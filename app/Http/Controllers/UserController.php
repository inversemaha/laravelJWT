<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request){
        $credentials = $request->only('email','password');

        try {
            if (! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'invalid_credentia;s'],400);
            }
        } catch (JWTException $e){
            return response()->json(['error'=> 'could_not_create_token'],500);
        }
        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors()->toJson(),500);
        }

        $user = User::create([
            'name'=> $request->get('name'),
            'email'=> $request->get('email'),
            'password'=> Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'),200);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate())
            {
                return response()->json(['user_not_found'],404);
            }
        }catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
        {
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
        {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch (Tymon\JWTAuth\Exceptions\JWTException $e)
        {
            return response()->json(['token_absent'],$e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    public function getUser()
    {
        $userList = User::all();
        return response()->json(compact('userList'));
    }


    public function update(Request $request , $id)
    {

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return response()->json($request);
/*
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors()->toJson(),500);
        }

        $user = User::findOrFail($id)->update([
            'name'=> $request->get('name'),
            'email'=> $request->get('email'),
            'password'=> Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'),200);*/
    }


    public function destroy($id)
    {

        $user = User::findOrFail($id);
        $user->delete();
        return response()->json($user);
    }

}
