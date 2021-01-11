<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\User;
use Hash;
use JWTAuth;


class ApiController extends Controller
{
    // token jwt login
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                // return response()->json(['error' => 'invalid_credentials'], 400);
                return response()->json([
                    'msg'       => 'failed login',
                    'error'     => 'email or password invalid',
                    'status'    => 400,
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'msg'       => 'invalid create token',
                'error'     => 'could_not_create_token', 
                'status'    => 500
            ], 500);
        }

        // return response()->json(compact('token'));
        return response()->json([
            'msg'       => 'user berhasil login',
            'data'      => $request->email,
            'token'     => $token,
            'status'    => 200
        ], 200);
    }

    // token jwt regis
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:25|unique:users',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',        
        ]);

        if($validator->fails()){
            // return response()->json($validator->errors()->toJson(), 400);
            return response()->json([
                'success'   => false, 
                'msg'       => $validator->errors()->toJson(),
                'status'    => 400,
            ], 400);
        }

        $user = User::create([
            'name'      => $request->get('name'),
            'email'     => $request->get('email'),
            'password'  => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'msg'       => 'succes register',
            'user'      => $user,
            'token'     => $token,
            'status'    => 201
        ],201);    
        // return response()->json(compact('user','token'),201);
    }
    // token jwt logout
    function logout()
    { 
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            // return response()->json(['success' => true, 'message' => 'Logout successful'], 200);
            return response()->json([
                'success' => true,
                'msg'  => 'Logout successful',
                'status' => 200
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false, 
                'msg' => 'Failed to logout, please try again',
                'status' => 500,
            ]);
        } 
    }

    public function getAuthenticatedUser()
    {
            try {

                    if (! $user = JWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            return response()->json(compact('user'));
    }

    public function open() 
    {
        $data = "This data is open and can be accessed without the client being authenticated";
        return response()->json(compact('data'),200);

    }

    public function closed() 
    {
        $data = "Only authorized users can see this";
        return response()->json(compact('data'),200);
    }

}
