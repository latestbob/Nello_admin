<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request) {

        $size = empty($request->size) ? 10 : $request->size;

        $feedbacks = Feedbacks::where('vendor_id', '=', $request->user()->vendor_id)->orderBy('id', 'desc');

        if (!empty($experience = $request->experience)) {
            $feedbacks = $feedbacks->where('experience', $experience);
        }

        if (!empty($phone = $request->phone)) {
            $feedbacks = $feedbacks->where('phone', 'like', "%{$phone}%");
        }

        $feedbacks = $feedbacks->paginate($size);

        $total = [
            'happy' => Feedbacks::where(['vendor_id' => $request->user()->vendor_id, 'experience' => 'happy'])->count('id'),
            'sad' => Feedbacks::where(['vendor_id' => $request->user()->vendor_id, 'experience' => 'sad'])->count('id'),
            'neutral' => Feedbacks::where(['vendor_id' => $request->user()->vendor_id, 'experience' => 'neutral'])->count('id')
        ];

        return view('feedback', compact('feedbacks', 'total', 'size', 'experience', 'phone'));
    }
}
