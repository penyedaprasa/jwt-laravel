<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon; 

class EventController extends Controller
{
    public function index(Request $request) 
    {
        $id = $request->id;
        if($id){
            $data = DB::table("v_events")->where([
                ['id', $id],
                ['tgl_berakhir', '>=', 'NOW()']
            ])->get(); 
        } else{
            $data = DB::table("v_events")->where('tgl_berakhir', '>=', 'NOW()')->get();
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
            'event_name'=>'required',
            'event_penyelenggara'=>'required',
            'description'=>'required',
            'tgl_mulai'=>'required',
            'jam_mulai'=>'required',
            'tgl_berakhir'=>'required',
            'jam_berakhir'=>'required',
            'total_ticket'=>'required',
            'image_url'=>'required',
            'total_ticket'=>'required',
            'price_ticket'=>'required',
            'location_address'=>'required',
            // 'lon'=>'required',
            // 'lat'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'  => 400,
                'msg'    => $validator->errors()
            ],200); 
        } else {
            $event_name = $request->event_name;
            $event_penyelenggara = $request->event_penyelenggara;
            $description = $request->description; 
            $tgl_mulai = date('Y-m-d', strtotime($request->tgl_mulai));
            $jam_mulai = $request->jam_mulai;
            $tgl_berakhir = date('Y-m-d', strtotime($request->tgl_berakhir)); 
            $jam_berakhir = $request->jam_berakhir;
            $price_ticket = $request->price_ticket;
            $total_ticket = $request->total_ticket;
            $image_url = $request->image_url;
            $location_address = $request->location_address;
            $lon = $request->lon;
            $lat = $request->lat;
            $event = Events::where('id',$request->id)->first();
            if(empty($event)){
                $event = new Events();
            }
            $event->event_name = $event_name;
            $event->event_penyelenggara = $event_penyelenggara;
            $event->description = $description;
            $event->save();
            if(empty($request->id)){
                DB::table('schedule_event')->insert([
                    'event_id' => $event->id,
                    'tgl_mulai' => $tgl_mulai,
                    'jam_mulai' => $jam_mulai,
                    'tgl_berakhir' => $tgl_berakhir,
                    'jam_berakhir' => $jam_berakhir,
                ]);
                DB::table('total_ticket')->insert([
                    'event_id' => $event->id,
                    'ticket_tersedia' => $total_ticket,
                    'total_ticket' => $total_ticket,
                    'price_ticket' => $price_ticket,
                ]);
                DB::table('image_event')->insert([
                    'event_id' => $event->id,
                    'image_url' => $image_url
                ]);
                DB::table('location_event')->insert([
                    'event_id' => $event->id,
                    'location_address' => $location_address,
                    'lon' => $lon,
                    'lat' => $lat,
                ]);
                return response()->json([
                    'status'  => 200,
                    'msg'     => 'succes create event',
                ],200); 
            } else{
                DB::table('schedule_event')->where('event_id', $request->id)->update([
                    // 'event_id' => $event->id,
                    'tgl_mulai' => $tgl_mulai,
                    'jam_mulai' => $jam_mulai,
                    'tgl_berakhir' => $tgl_berakhir,
                    'jam_berakhir' => $jam_berakhir,
                ]);
                DB::table('total_ticket')->where('event_id', $request->id)->update([
                    // 'event_id' => $event->id,
                    'ticket_tersedia' => $total_ticket
                ]);
                DB::table('image_event')->where('event_id', $request->id)->update([
                    // 'event_id' => $event->id,
                    'image_url' => $image_url
                ]);
                DB::table('location_event')->where('event_id', $request->id)->update([
                    // 'event_id' => $event->id,
                    'location_address' => $location_address,
                    'lon' => $lon,
                    'lat' => $lat,
                ]);               
                return response()->json([
                    'status'  => 200,
                    'msg'     => 'succes update event',
                ],200); 
            }
            
        } //else validator
    }

    public function delete($id)
    {
        $check = DB::table('events')->where('id', $id)->first();
        if(empty($check)){
            return response()->json([
                'status'  => 400,
                'msg'     => 'error, event not found!'
            ],400);  
        }
        DB::beginTransaction();
        try {
            DB::table('events')->where('id',$id)->delete();
            DB::table('schedule_event')->where('event_id',$id)->delete();
            DB::table('total_ticket')->where('event_id',$id)->delete();
            DB::table('image_event')->where('event_id',$id)->delete();
            DB::table('location_event')->where('event_id',$id)->delete();
            DB::commit();
            // toast()->success('Data berhasil di hapus', $this->title);
            // return redirect()->route($this->view . '.index');
            return response()->json([
                'status'  => 200,
                'msg'     => 'success delete event',
            ],200); 
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
        // toast()->error('Terjadi Kesalahan', $this->title);
        // return redirect()->back();
        return response()->json([
            'status'  => 400,
            'msg'     => 'Terjadi Kesalahan',
        ],400); 
    }
}
