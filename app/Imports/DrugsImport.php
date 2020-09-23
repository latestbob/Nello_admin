<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\PharmacyDrug as Drug;
use App\Models\PharmacyDrug;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DrugsImport implements ToModel, WithHeadingRow
{

    public function headingRow() : int 
    {
        return 1;
    }

    public function model(array $row)
    {
        dd($row);

        return new PharmacyDrug([
            'sku' => $row['drug_id'],
            'name' => $row['item_name'],
            'brand' => $row['brand'],
            'category_id' => $row['category_id'],
            'dosage_type' => $row['dosage_form'],
            'price' => (double) str_replace(',', '', $row['price']),
            'require_prescription' => strtolower($row['require_prescription']) == 'no' ? 0 : 1,
            'description' => $row['description'],
            'vendor_id' => 1,
            'uuid' => Str::uuid()->toString()
        ]);
    }

    public function collection(Collection $rows)
    {
        $drugs = [];
        foreach ($rows as $row)
        {
            if (!empty(trim($row[0])) && is_numeric($row[3])) {
                $drugs[] = [
                    'sku' => $row[0],
                    'name' => $row[1],
                    'brand' => $row[2],
                    'category_id' => $row[3],
                    'dosage_type' => $row[4],
                    'price' => (double) str_replace(',', '', $row[5]),
                    'require_prescription' => strtolower($row[6]) == 'no' ? 0 : 1,
                    'description' => $row[7],
                    'vendor_id' => 1,
                    'uuid' => Str::uuid()->toString()
                ];
            }
        }

        Drug::insert($drugs);
    }
}
