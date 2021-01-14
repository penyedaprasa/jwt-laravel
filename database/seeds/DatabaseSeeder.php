<?php

use Illuminate\Database\Seeder;
use App\Roles;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Roles::insert([ 
            [
                "name"        => "Super Admin",
                "description" => "Super Admin"
            ],
            [
                "name"        => "Event Organizer",
                "description" => "Event Organizer"
            ],
            [
                "name"        => "User",
                "description" => "User"
            ]
        ]);
        
        User::insert([
            [
                'name'      => 'Super Admin',
                'email'     => 'supadmin@local.host',
                'role_id'   => 1,
                'password'  => Hash::make('adminadmin'),
            ],
            [
                'name'      => 'Admin',
                'email'     => 'admin@local.host',
                'role_id'   => 2,
                'password'  => Hash::make('adminadmin'),
            ],
            [
                'name'      => 'User',
                'email'     => 'user@local.host',
                'role_id'   => 3,
                'password'  => Hash::make('adminadmin'),
            ]
        ]);

        DB::table('order_status')->insert([
            [
                'name'      => 'Menunggu pembayaran',
                'description'     => 'Menunggu pembayaran',
            ],
            [
                'name'      => 'Sudah dibayar',
                'description'     => 'Sudah dibayar',
            ],
            [
                'name'      => 'Finish',
                'description'     => 'Finish',
            ],
            [
                'name'      => 'Cancel',
                'nadescriptionme'      => 'Cancel',
            ]
        ]);
    }
}
