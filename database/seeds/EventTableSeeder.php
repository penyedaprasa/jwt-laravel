<?php

use Illuminate\Database\Seeder;
use App\Events;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 3000; $i++) {    
        
            $event = new Events();
            $event->event_name ="event ".str_random(3);
            $event->event_penyelenggara = 'penyelenggara'.str_random(3);
            $event->description = 'description';
            $event->category_event_id = 1;
            $event->save();

            DB::table('schedule_event')->insert([
                'event_id' => $event->id,
                'tgl_mulai' => '2021-02-07',
                'jam_mulai' => '14:00',
                'tgl_berakhir' => '2021-02-07',
                'jam_berakhir' => '22:00',
            ]);
            DB::table('total_ticket')->insert([
                'event_id' => $event->id,
                'ticket_tersedia' => 1000,
                'total_ticket' => 1000,
                'price_ticket' => 100000,
            ]);
            DB::table('image_event')->insert([
                'event_id' => $event->id,
                // 'image_url' => $image_url,
                'image_url' => 'https://png.pngtree.com/png-clipart/20200908/ourmid/pngtree-creative-design-cartoon-emoji-package-event-png-image_2335507.jpg',
                'image_url' => 'https://png.pngtree.com/png-clipart/20200908/ourmid/pngtree-creative-design-cartoon-emoji-package-event-png-image_2335507.jpg',
            ]);
            DB::table('location_event')->insert([
                'event_id' => $event->id,
                'location_address' => 'Jakarta',
                'lon' => 1,
                'lat' => 1,
            ]);

        }

        for ($i=1; $i < 21; $i++) {    
        
            $event = new Events();
            $event->event_name ="event ".str_random(3);
            $event->event_penyelenggara = 'penyelenggara'.str_random(3);
            $event->description = 'description';
            $event->category_event_id = 1;
            $event->save();

            DB::table('schedule_event')->insert([
                'event_id' => $event->id,
                'tgl_mulai' => '2021-02-'.$i,
                'jam_mulai' => '14:00',
                'tgl_berakhir' => '2021-02-'.$i,
                'jam_berakhir' => '22:00',
            ]);
            DB::table('total_ticket')->insert([
                'event_id' => $event->id,
                'ticket_tersedia' => 1000,
                'total_ticket' => 1000,
                'price_ticket' => 100000,
            ]);
            DB::table('image_event')->insert([
                'event_id' => $event->id,
                // 'image_url' => $image_url,
                'image_url' => 'https://png.pngtree.com/png-clipart/20200908/ourmid/pngtree-creative-design-cartoon-emoji-package-event-png-image_2335507.jpg',
                'image_url' => 'https://png.pngtree.com/png-clipart/20200908/ourmid/pngtree-creative-design-cartoon-emoji-package-event-png-image_2335507.jpg',
            ]);
            DB::table('location_event')->insert([
                'event_id' => $event->id,
                'location_address' => 'Jakarta',
                'lon' => 1,
                'lat' => 1,
            ]);

        }

        for ($i=1; $i < 21; $i++) {    
        
            $event = new Events();
            $event->event_name ="event ".str_random(3);
            $event->event_penyelenggara = 'penyelenggara'.str_random(3);
            $event->description = 'description';
            $event->category_event_id = 2;
            $event->save();

            DB::table('schedule_event')->insert([
                'event_id' => $event->id,
                'tgl_mulai' => '2021-02-'.$i,
                'jam_mulai' => '14:00',
                'tgl_berakhir' => '2021-02-'.$i,
                'jam_berakhir' => '22:00',
            ]);
            DB::table('total_ticket')->insert([
                'event_id' => $event->id,
                'ticket_tersedia' => 1000,
                'total_ticket' => 1000,
                'price_ticket' => 100000,
            ]);
            DB::table('image_event')->insert([
                'event_id' => $event->id,
                'image_url' => asset('media/avatar/default.jpg'),
                'image_url' => 'https://png.pngtree.com/png-clipart/20200908/ourmid/pngtree-creative-design-cartoon-emoji-package-event-png-image_2335507.jpg',
            ]);
            DB::table('location_event')->insert([
                'event_id' => $event->id,
                'location_address' => 'Jakarta',
                'lon' => 1,
                'lat' => 1,
            ]);

        }

        for ($i=1; $i < 21; $i++) {    
        
            $event = new Events();
            $event->event_name ="event ".str_random(3);
            $event->event_penyelenggara = 'penyelenggara'.str_random(3);
            $event->description = 'description';
            $event->category_event_id = 3;
            $event->save();

            DB::table('schedule_event')->insert([
                'event_id' => $event->id,
                'tgl_mulai' => '2021-02-'.$i,
                'jam_mulai' => '14:00',
                'tgl_berakhir' => '2021-02-'.$i,
                'jam_berakhir' => '22:00',
            ]);
            DB::table('total_ticket')->insert([
                'event_id' => $event->id,
                'ticket_tersedia' => 1000,
                'total_ticket' => 1000,
                'price_ticket' => 100000,
            ]);
            DB::table('image_event')->insert([
                'event_id' => $event->id,
                'image_url' => 'https://png.pngtree.com/png-clipart/20200908/ourmid/pngtree-creative-design-cartoon-emoji-package-event-png-image_2335507.jpg',
            ]);
            DB::table('location_event')->insert([
                'event_id' => $event->id,
                'location_address' => 'Jakarta',
                'lon' => 1,
                'lat' => 1,
            ]);

        }
        


    }
}
