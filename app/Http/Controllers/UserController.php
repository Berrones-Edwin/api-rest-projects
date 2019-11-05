<?php

namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = User::where('email',$credentials['email'])->get();
        return response()->json(compact('token','user'));
        //
        // $credentials = $request->only('email','password');

        // if (! $token = auth()->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return $this->respondWithToken($token);
       
        // $credentials = $request->only('email','password');

        // $validator = Validator::make($credentials,[
        //     'email' =>'required|email',
        //     'password' =>'required'
        // ]);

        // if($validator->fails())
        //     return response()->json([
        //         'success' =>false,
        //         'message' =>'Wrong Validation',
        //         'error' => $validator->errors()
        //     ],422);
            
        // $token =JWTAuth::attempt($credentials);
        // if(!$token)
            
        //     return response()->json([
        //         'success' =>false,
        //         'message' =>'Wrong credentials',
        //         'error' => 'User fail'
        //     ],401);
            
        // return response()->json([
        //     'success' =>true,
        //     'token' =>$token,
        //     'user' => User::where('email',$credentials['email'])->get()
        // ],200);
            
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
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
        try {
            // JWTAuth::invalidate($token);
            JWTAuth::parseToken()->invalidate();
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
