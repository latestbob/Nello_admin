<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartnersController extends Controller
{
    public function index(Request $request)
    {
        $partners = Partner::paginate($request->size ?? 15);

        return $partners;
    }


    public function create(PartnerRequest $request)
    {
        $data = $request->validated();
        $data['api_key'] = bcrypt(Str::random(64));
        Partner::create($data);
    }


}
