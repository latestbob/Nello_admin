<?php

namespace App\Http\Controllers;

use App\Models\HealthTip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HealthTipController extends Controller
{
    public function index(Request $request)
    {
        $size = empty($request->size) ? 10 : $request->size;
        $search = $request->search;
        $currentYear = Carbon::today()->year;
        $year = $request->year ?? $currentYear;
        $month = $request->month ?? Carbon::today()->month;

        $tips = HealthTip::when($search, function ($query, $search) {
            $query->whereRaw("(title like ? or body like ?)", ["%{$search}%", "%{$search}%"]);
        })->when($year, function ($query, $year) {
            $query->whereYear('date', $year);
        })->when($month, function ($query, $month) {
            $query->whereMonth('date', $month);
        })->paginate($size);

        $years = [];
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        for ($i = $currentYear; $i > ($currentYear - 10); $i--) {
            $years[] = $i;
        }

        return view('health-tips', compact('tips', 'size', 'search', 'year', 'month', 'years', 'months'));
    }

    public function addTip(Request $request)
    {
        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'title' => 'required|string|max:50',
                'body' => 'required|string',
                'date' => 'required|date|date_format:d-m-Y'
            ])->validate();

            $data['date'] = Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d');

            $data['uuid'] = Str::uuid()->toString();

            $data['vendor_id'] = $request->user()->vendor_id;

            $drug = HealthTip::create($data);

            return redirect("health-tip/{$drug->uuid}/view")->with('success', "Health tip has been added successfully");

        }

        return view('health-tip-add');
    }

    public function viewTip(Request $request)
    {
        if (empty($uuid = $request->uuid)) {
            return redirect('/health-tips')->with('error', "Health Tip ID missing");
        }

        $tip = HealthTip::where('uuid', $uuid)->first();

        if (empty($tip)) {
            return redirect('/health-tips')->with('error', "Sorry, the ID '{$uuid}' is not associated with any health tip");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'title' => 'required|string|max:50',
                'body' => 'required|string',
                'date' => 'required|date|date_format:d-m-Y'
            ])->validate();

            $data['date'] = Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d');

            $tip->update($data);

            session()->put('success', "Health tip updated successfully");
        }

        return view('health-tip-view', compact('tip', 'uuid'));
    }
}
