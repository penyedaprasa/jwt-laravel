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
        DB::statement("CREATE VIEW v_events AS SELECT a.id, a.event_name FROM `events` a JOIN image_event b ON `a`.id = b.event_id JOIN schedule_event c ON a.id = c.event_id JOIN total_ticket d ON a.id = d.event_id");
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
