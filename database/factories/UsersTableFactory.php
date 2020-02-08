<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    $blood_group = ['A+','B+','AB+','O+','A-','B-','AB-','O-'];
    // $address_list = [
    //     'Mohakhali,Amtolle,Road NO:5',
    //     'Farm Gate,RH Home-Center,Road NO:78',
    //     'New-Market,Puran Gole,Road NO:45',
    //     'Nukunjo,Road NO:11',
    //     'Kilkhat,LE Meridien,Road NO:5',
    //     'Nukunjo,LE Meridien,Road NO:1',
    //     'Ptua Tole,Puran Dhaka',
    //     'Nukunjo,Road NO:9'
    // ];

    $address_list = [
        'Head Office',
        'Branch Office'
    ];
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone'=>rand(11,100),
        'address'=>$address_list[rand(0,1)],
        'blood_group'=>$blood_group[rand(0,7)],
        'latest_donotion_date'=>'2019-5-14 11:28:28',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'activation_token'=>'secret',
    ];
});
