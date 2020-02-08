<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone'=>'01234567',
            'address'=>"Branch Office",
            'blood_group'=>"B+",
            'role'=>'admin',
            'latest_donotion_date'=>'2019-5-14 11:28:28',
            'active_status' =>1,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'activation_token'=>'secret',
        ]);
        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user@user.com',
            'phone'=>'01234567',
            'address'=>"Branch Office",
            'blood_group'=>"B+",
            'role'=>'user',
            'latest_donotion_date'=>'2019-5-14 11:28:28',
            'active_status' =>1,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'activation_token'=>'secret',
        ]);
        $users = factory(User::class, 100)->create();
    }
}
