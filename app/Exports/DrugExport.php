<?php

namespace App\Exports;

use App\Models\PharmacyDrug;
use Maatwebsite\Excel\Concerns\FromCollection;

class DrugExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PharmacyDrug::all();
    }
}
