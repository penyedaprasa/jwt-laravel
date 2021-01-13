<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW if exists v_users");
        DB::statement("CREATE VIEW v_users AS SELECT users.id, users.name, users.email, roles.name as role_name, roles.description as role_description, b.gender, b.phone, b.address, b.created_at, b.updated_at FROM users JOIN roles ON users.role_id = roles.id LEFT JOIN user_profile b ON  users.id = b.user_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('v_users');
    }
}
