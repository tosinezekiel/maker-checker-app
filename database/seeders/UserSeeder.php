<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'first_name' => 'john' ,
                'last_name' => 'doe' ,
                'email' => 'johndoe@example.com' ,
                'password' => bcrypt('Password1!'),
                'role_id' => Role::ADMIN
            ],
            [
                'first_name' => 'james' ,
                'last_name' => 'doe' ,
                'email' => 'jamesdoe@example.com' ,
                'password' => bcrypt('Password1!'),
                'role_id' => Role::ADMIN
            ],
            [
                'first_name' => 'grace' ,
                'last_name' => 'doe' ,
                'email' => 'gracedoe@example.com' ,
                'password' => bcrypt('Password1!'),
                'role_id' => Role::ADMIN
            ]
        ]);
    }
}
