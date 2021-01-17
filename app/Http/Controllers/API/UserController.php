<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Events;
use App\User;
use Hash;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon; 


class UserController extends Controller
{
    public function index(Request $request) 
    {
        $id = $request->id;
        if($id){
            $data = DB::table("v_users")->where([
                ['id', $id]
            ])->get(); 
        } else{
            $data = DB::table("v_users")->get();
        }
        return response()->json([
            'status'  => 200,
            'msg'     => 'succes',
            'data'     => $data,
        ],200);   
    }

    public function editProfile(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'gender'=>'in:male,female',
            'phone'=>'required',
            'address'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'  => 400,
                'msg'    => $validator->errors()
            ],200); 
        } else {
            $gender = $request->gender;
            $phone = $request->phone;
            $address = $request->address;

            $photo = $request->input('avatar');
            if($photo){
                $photo = str_replace('data:image/png;base64,', '', $photo);
                $photo = str_replace(' ', '+', $photo);
                $data = base64_decode($photo);
                // $photo = base64_decode($photo);
                
                $savename = time();
                $savename = md5($savename).'.PNG';
                // file upload to directory
                $path = public_path().'/storage/avatar/';
                if (!File::exists($path)){
                    File::makeDirectory($path);
                    file_put_contents("storage/avatar/".$savename, $data);
                }else{
                    file_put_contents("storage/avatar/".$savename, $data);
                }
                $avatar = asset('storage/avatar/'.$savename);
            } else{
                $avatar = asset('media/avatar/default.jpg');
            }

            $user = DB::table('users')->where('id', $id)->first();
            if(empty($user)){
                return response()->json([
                    'status'  => 400,
                    'msg'     => 'user not found.',
                ],400);   
            }
            $profile = DB::table('user_profile')->where('user_id', $user->id)->first();
            if(empty($profile)){
                DB::table('user_profile')->insert([
                    'user_id' => $user->id,
                    'gender' => $gender,
                    'phone' => $phone,
                    'address' => $address,
                    'avatar' => $avatar,
                ]);
            } else{
                DB::table('user_profile')->
                where('user_id', $user->id)->update([
                    'gender' => $gender,
                    'phone' => $phone,
                    'address' => $address,
                    'avatar' => $avatar,
                ]);
            }
            return response()->json([
                'status'  => 200,
                'msg'     => 'succes edit profile.',
            ],200);  
        } //validator
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:25',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',      
        ]);

        if($validator->fails()){
            // return response()->json($validator->errors()->toJson(), 400);
            return response()->json([
                'status'    => 400,
                'success'   => false, 
                'msg'       => $validator->errors()->toJson(),
            ], 400);
        }

        $user = User::create([
            'name'      => $request->get('name'),
            'email'     => $request->get('email'),
            'role_id'     => 3,
            'password'  => Hash::make($request->get('password')),
        ]);

        return response()->json([
            'status'    => 200,
            'msg'       => 'succes create user.',
        ],200);   
    }

}
