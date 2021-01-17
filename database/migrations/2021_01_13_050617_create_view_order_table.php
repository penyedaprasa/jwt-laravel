<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW if exists v_orders");
        DB::statement("CREATE VIEW v_orders AS SELECT a.id, a.order_code, a.user_id, d.name, a.event_id, b.event_name, b.category_event, b.event_penyelenggara, b.description, b.image_url, b.tgl_mulai, b.jam_mulai, b.tgl_berakhir, b.jam_berakhir, a.ticket_order, a.total_price_ticket, b.ticket_tersedia, b.total_ticket, b.location_address, c.`name` AS `status`, a.created_at, a.updated_at FROM `event_order` a JOIN v_events b ON `a`.event_id = b.id JOIN order_status c ON a.status_id = c.id JOIN v_users d ON a.user_id = d.id");
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('v_orders');
    }
}
