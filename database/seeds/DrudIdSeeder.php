<?php

use App\Models\PharmacyDrug;
use Illuminate\Database\Seeder;

class DrudIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drugs = PharmacyDrug::all();
        foreach($drugs as $drug) {
            $drugId = "AN" .  random_int(100000, 999999);
            $drug->update(['drug_id' => $drugId]);
        }
    }
}
