<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DoctorImport implements ToModel, WithHeadingRow
{

    public function headingRow() : int
    {
        return 1;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $names = explode(' ', $row['medical_director']);
        $count = count($names);
        if (isset($row['medical_director'])) {
            return new User([
                'uuid'      => Str::uuid()->toString(),
                'firstname' => $names[1],
                'middlename' => $count > 3 ? $names[2] : '',
                'lastname' => $count > 3 ? $names[3] : ($count == 3 ? $names[2] : '') ,
                'phone' => $row['tel_no'],
                'email' => $row['email_address'],
                'password' => Hash::make(Str::random(8)),
                'address' => $row['address'],
                'hospital' => $row['hospital'],
                'picture' => '',
                //'ufield' => '',
                'aos' => $row['specialization'],
                'gender' => $row['gender'],
                'user_type' => 'doctor',
                'vendor_id' => 1
            ]);
        }
    }
}
