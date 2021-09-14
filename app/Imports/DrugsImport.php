<?php

namespace App\Imports;

use App\Models\DrugCategory;
use Illuminate\Support\Str;
use App\Models\PharmacyDrug;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DrugsImport implements ToModel, WithHeadingRow
{

    private $count = 0;

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        if (!is_numeric($row['category']) && empty($row['category'])) {
            $this->count++;
            print_r($row);
            echo "\n{$this->count} \n";
            //     $category = DrugCategory::where('name', $row['category'])->first();
            // if (!$category) {
            //     $category = DrugCategory::create([
            //         'name' => $row['category']
            //     ]);
            // }

            // if (isset($row['drug_id'])) {
            //     return new PharmacyDrug([
            //         'sku' => $row['drug_id'],
            //         'name' => $row['item_name'],
            //         'brand' => $row['brand'],
            //         'quantity' => $row['quantity'],
            //         'category_id' => $category->id,
            //         'dosage_type' => $row['dosage_form'],
            //         'price' => (float) str_replace(',', '', $row['price']),
            //         'require_prescription' => strtolower($row['require_prescription']) == 'no' ? 0 : 1,
            //         'description' => $row['description'],
            //         'vendor_id' => 1,
            //         'uuid' => Str::uuid()->toString()
            //     ]);
            // }
        }
    }
}
