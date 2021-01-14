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
    // random string
    function randomString() {
        $length = 6;
        $stre = "";
        $characters = array_merge(range('A','Z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $stre .= $characters[$rand];
        }
        return $stre;
    }

    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $id = $request->order_code;
        if($user_id){
            if($id){
                $data = DB::table("v_orders")->where([['order_code', $id],['user_id', $user_id]])->get(); 
            } else{
                $data = DB::table("v_orders")->where('user_id', $user_id)->get();
            }
        } else{
            if($id){
                $data = DB::table("v_orders")->where('order_code', $id)->get(); 
            } else{
                $data = DB::table("v_orders")->get();
            }
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
            $event->order_code = $this->randomString();
            $event->user_id = $user_id;
            $event->event_id = $event_id;
            $event->status_id = 1;
            $event->ticket_order = $request->ticket_order;
            
            // get tiket tersedia
            $jumlah = DB::table('total_ticket')->where('event_id', $event_id)->first();
            $result = $jumlah->total_ticket - $request->ticket_order;
            $price = $jumlah->price_ticket * $request->ticket_order;
            $event->total_price_ticket = $price;
            if($result >= 0){
                $event->save();
                DB::table('total_ticket')->where('event_id', $event_id)->update([
                    'ticket_tersedia' => $result
                ]);
                // DB::table('event_order')->where('id', $event->id)->update([
                //     'total_price_ticket' => $price
                // ]);
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
            'order_code'=>'required',
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
            // $event = Orders::where('id',$request->id)->first();
            $event = Orders::where('order_code',$request->order_code)->first();
            if(empty($event)){
                return response()->json([
                    'status'  => 400,
                    'msg'    => 'event not found.'
                ],400);
            }
            // $check_user = Orders::where([['id',$request->id], ['user_id', $request->user_id], ['event_id', $request->event_id]])->first();
            $check_user = Orders::where([['order_code',$request->order_code], ['user_id', $request->user_id], ['event_id', $request->event_id]])->first();
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

    public function delete(Request $request, $code)
    {
        $check = DB::table('event_order')->where('order_code', $code)->first();
        if(empty($check)){
            return response()->json([
                'status'  => 400,
                'msg'     => 'error, event not found!'
            ],400);  
        }
        // dd($check);
        DB::beginTransaction();
        try {
            DB::table('event_order')->where('order_code',$code)->delete();
            $total_tersedia = DB::table('total_ticket')->where('event_id', $check->event_id)->first();
            $ticket = $check->ticket_order + $total_tersedia->ticket_tersedia;
            DB::table('total_ticket')->where('event_id', $check->event_id)->update([
                'ticket_tersedia' => $ticket
            ]);
            DB::commit();
            // toast()->success('Data berhasil di hapus', $this->title);
            // return redirect()->route($this->view . '.index');
            return response()->json([
                'status'  => 200,
                'msg'     => 'success delete event',
            ],200); 
        } catch (\Exception $e) {
            DB::rollback();
        }
        // toast()->error('Terjadi Kesalahan', $this->title);
        // return redirect()->back();
        return response()->json([
            'status'  => 400,
            'msg'     => 'Terjadi Kesalahan',
        ],400); 
    }
}
    