<?php

use App\Models\Partner;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::create([
            'name' => 'Tremendoc',
            'email' => 'admin@tremendoc.com',
            'api_key' => Str::lower(Str::random(16))
        ]);
    }
}
