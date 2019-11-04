<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpireException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\User;

class UserController extends Controller
{
    
    public function login(Request $request)
    {
        //
        // $credentials = $request->only('email','password');

        // if (! $token = auth()->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return $this->respondWithToken($token);
       
        $credentials = $request->only('email','password');

        $validator = Validator::make($credentials,[
            'email' =>'required|email',
            'password' =>'required'
        ]);

        if($validator->fails())
            return response()->json([
                'success' =>false,
                'message' =>'Wrong Validation',
                'error' => $validator->errors()
            ],422);
            
        $token =JWTAuth::attempt($credentials);
        if(!$token)
            
            return response()->json([
                'success' =>false,
                'message' =>'Wrong credentials',
                'error' => 'User fail'
            ],401);
            
        return response()->json([
            'success' =>true,
            'token' =>$token,
            'user' => User::where('email',$credentials['email'])->get()
        ],200);
            
    }

    public function refresh()
    {
        //

        $token= JWTAuth::getToken();
        try {
            $token = JWTAuth::refresh($token);
            return response()->json([
                 'success' =>true,
                 'token' =>$token
            ],200);

        }catch (JWTException $ex) {
            return response()->json([
                    'success' =>false,
                    'message' =>'JWTException Need to login again!',
                    'errors' => $ex
            ],422);
        
        }  catch (TokenExpireException $ex) {
            return response()->json([
                    'success' =>false,
                    'message' =>'TokenExpireException. Need to login again!',
                    'errors' => $ex
            ],422);
        
        } catch (TokenBlacklistedException $ex) {
            return response()->json([
                    'success' =>false,
                    'message' =>'TokenBlacklistedException. Need to login again!',
                    'errors' => $ex
            ],422);
        }
    }

    
    public function logout()
    {
        //

        $token = JWTAuth::getToken();
        try {
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => ' Logout Successfull'
            ],200);
            
        } catch (JWTException $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => ' Failed Logout, please try again!'
            ],422);
        }
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            
        ]);
    }
}
