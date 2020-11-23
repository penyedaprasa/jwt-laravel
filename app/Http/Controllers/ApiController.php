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
    public function postLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'msg'       => 'failed login',
                'error'     => $validator->errors(),
                'status'    => 400,
            ]);
        }

        // dd(encrypt($request->password));
        if($data = User::with('group')->where('email', '=', $request->email)->first()){

            // use hash encrypt
            if (Hash::check($request->password, $data->password)){
            
            // use encrypt  
            // if(encrypt($data->password)==$request->password){
                return response()->json([
                    'msg'       => 'success login as '. $data->group->group_name,
                    'user'      => $data,
                    'status'    => 200,
                ]);
            }
        }
        return response()->json([
            'msg'    => 'failed login',
            'error'  => 'email or password invalid',
            'status' => 400,
        ]);
    }

    public function postRegister(Request $request){
        dd('hahahahaahaa');
        $validator = Validator::make($request->all(), [
            'email'            => 'required',
            // 'email'         => 'required|string|users_email|max:255|unique:pms_users',
            'password'         => 'required',
            'nama'             => 'required',
            // 'alamat'        => 'required',
            // 'no_hp'         => 'required'       
        ]);

        if($validator->fails()){
            return response()->json([
                'msg'         => 'failed create user',
                'error'       => $validator->errors(),
                'status'      => 401
            ]);
        }
        $create = User::create([
            'name'      => $request->nama,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            // 'password'  => encrypt($request->password),
            'group_id'        => $request->group_id,

        ]);
        if($create){
            return response()->json([
                'msg'     => 'user created',
                'user'    => $create,
                'status'  => 200
            ]);
        }
        return response()->json([
            'msg'  => 'failed',
            'error' => 'something error',
            'status' => 400
        ]);
    }

    // token jwt login
    public function authenticate(Request $request)
        {
            $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    // return response()->json(['error' => 'invalid_credentials'], 400);
                    return response()->json([
                        'msg'  => 'failed login',
                        'error' => 'email or password invalid',
                        'status' => 400,
                    ]);
                }
            } catch (JWTException $e) {
                return response()->json([
                    'msg'   => 'invalid create token',
                    'error' => 'could_not_create_token', 
                    'status' => 500
                ]);
            }

            // return response()->json(compact('token'));
            return response()->json([
                'msg'     => 'user berhasil login',
                'user'     => $request->email,
                'token'    => $token,
                'status'  => 200
            ]);
        }

    // token jwt regis
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',        
        ]);

        if($validator->fails()){
            // return response()->json($validator->errors()->toJson(), 400);
            return response()->json([
                'success' => false, 
                'msg' => $validator->errors()->toJson(),
                'status' => 400,
            ]);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
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
