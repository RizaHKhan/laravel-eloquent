<?php

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
        factory(App\User::class, 3)->create();
        // Or do something like this to create addresses
        /* ->each(function ($user) { */
        /*     $user->addresss()->save(factory(App\Address::class)->make()); */
        /* }); */
    }
}
