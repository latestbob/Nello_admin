<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\PharmacyDrug as Drug;
use Maatwebsite\Excel\Concerns\ToCollection;

class DrugsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $drugs = [];
        foreach ($rows as $row) 
        {
            if (!empty(trim($row[0]))) {
                $drugs[] = [
                    'sku' => $row[0],
                    'name' => $row[1],
                    'brand' => $row[2],
                    'category_id' => $row[3],
                    'dosage_type' => $row[4],
                    'price' => (double) str_replace(',', '', $row[5]),
                    'require_prescription' => strtolower($row[6]) == 'no' ? 0 : 1,
                    'description' => $row[7],
                    'uuid' => Str::uuid()->toString()
                ];
            }
        }

        Drug::insert($drugs);
    }
}
