<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use DB;

class EventController extends Controller
{
    public function index() 
    {
        $data = DB::table("v_events")->get();
        // $data = Events::all();
        return response()->json([
            'msg'     => 'succes',
            'data'     => $data,
            'status'  => 200
        ],200);   
    }

    public function create(Request $request)
    {
        $event = Events::where('event_name',$request->title)->first();
        if(empty($event)){
            $event = new Events();
            $event->event_name = "event music";
            $event->event_penyelenggara = "event music";
            $event->description = "event music";
        }
        $event->save();
        if($event){
            DB::table('schedule_event')->insert([
                'event_id' => $event->id,
                'tgl_mulai' => "2021-01-05",
                'jam_mulai' => "14:00",
                'tgl_berakhir' => "2021-01-05",
                'jam_berakhir' => "22:00",
            ]);
            DB::table('total_ticket')->insert([
                'event_id' => $event->id,
                'total_ticket' => 100
            ]);
            DB::table('image_event')->insert([
                'event_id' => $event->id,
                'image_url' => 'http://localhost/image.png'
            ]);
            DB::table('location_event')->insert([
                'event_id' => $event->id,
                'location_address' => 'Jakarta',
                'lon' => '111,1',
                'lat' => '151,1',
            ]);
        }
        return response()->json([
            'msg'     => 'succes create event',
            'status'  => 200
        ],200);   
    }

    public function edit(Request $request)
    {
        
    }

}
