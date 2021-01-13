<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use App\Orders;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon; 

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;
        if($id){
            $data = DB::table("v_orders")->where('id', $id)->get(); 
        } else{
            $data = DB::table("v_orders")->get(); 
        }
        return response()->json([
            'status'  => 200,
            'msg'     => 'succes',
            'data'     => $data,
        ],200);   
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'event_id'=>'required',
            'user_id'=>'required',
            'ticket_order'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'  => 400,
                'msg'    => $validator->errors()
            ],400); 
        } else {
            $event_id = $request->event_id;
            $user_id = $request->user_id;
            
            $event = new Orders();
            $event->user_id = $user_id;
            $event->event_id = $event_id;
            $event->status_id = 1;
            $event->ticket_order = $request->ticket_order;
            
            // get tiket tersedia
            $jumlah = DB::table('total_ticket')->where('event_id', $event_id)->first();
            $result = $jumlah->total_ticket - $request->ticket_order;
            $price = $jumlah->price_ticket * $request->ticket_order;
            if($result >= 0){
                $event->save();
                DB::table('total_ticket')->where('event_id', $event_id)->update([
                    'ticket_tersedia' => $result
                ]);
                DB::table('event_order')->where('id', $event->id)->update([
                    'total_price_ticket' => $price
                ]);
                return response()->json([
                    'status'  => 200,
                    'msg'     => 'succes order event',
                ],200);        
            }
            return response()->json([
                'status'  => 400,
                'msg'     => 'failed order event',
            ],400); 
            
                
        } //else validator
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id'=>'required',
            'event_id'=>'required',
            'user_id'=>'required',
            'status_id'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'  => 400,
                'msg'    => $validator->errors()
            ],400); 
        } else {
            $event_id = $request->event_id;
            $user_id = $request->user_id;
            $event = Orders::where('id',$request->id)->first();
            if(empty($event)){
                return response()->json([
                    'status'  => 400,
                    'msg'    => 'event not found.'
                ],400);
            }
            $check_user = Orders::where([['id',$request->id], ['user_id', $request->user_id], ['event_id', $request->event_id]])->first();
            if(empty($check_user)){
                return response()->json([
                    'status'  => 400,
                    'msg'    => 'The data is not match.'
                ],400);
            }
            $event->user_id = $user_id;
            // $event->event_id = $event_id;
            $event->status_id = $request->status_id;
            $event->save();
            return response()->json([
                'status'  => 200,
                'msg'     => 'succes update order event',
            ],200); 
            
        } //else validator
    }
}
    