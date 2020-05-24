<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendors = \App\Models\Vendor::all();
        foreach ($vendors as  $vendor) {

            $faker = \Faker\Factory::create();

            $user = new \App\Models\User();
            $user->firstname = $faker->firstName;
            $user->lastname = $faker->lastName;
            $user->email = $faker->email;
            $user->phone = $faker->e164PhoneNumber;
            $user->picture = "http://www.famacare.com/img/famacare.png";
            $user->password = \Illuminate\Support\Facades\Hash::make("password");
            $user->user_type = 'admin';
            $user->vendor_id = $vendor->id;
            $user->save();
        }
    }
}
