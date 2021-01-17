<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW if exists v_events");
        DB::statement("CREATE VIEW v_events AS SELECT a.id, a.event_name, f.name AS category_event, a.event_penyelenggara, a.description, b.image_url, c.tgl_mulai, c.jam_mulai, c.tgl_berakhir, c.jam_berakhir, d.price_ticket AS harga_ticket, d.ticket_tersedia, d.total_ticket, e.location_address, a.created_at, b.updated_at FROM `events` a JOIN image_event b ON `a`.id = b.event_id JOIN schedule_event c ON a.id = c.event_id JOIN total_ticket d ON a.id = d.event_id JOIN location_event e ON a.id = e.event_id JOIN event_category f ON a.category_event_id = f.id");
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('v_events');
    }
}
