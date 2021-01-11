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
                "name"        => "Admin",
                "description" => "Admin"
            ],
            [
                "name"        => "Client",
                "description" => "Client"
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
                'name'      => 'Client',
                'email'     => 'client@local.host',
                'role_id'   => 3,
                'password'  => Hash::make('adminadmin'),
            ]
        ]);
    }
}
