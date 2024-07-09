<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use DB;
class FeedbackController extends Controller
{
    public function index(Request $request) {

       // $feedbacks = DB::table("customerfeedbacks")->truncate();

        $size = empty($request->size) ? 10 : $request->size;

        $feedbacks = DB::table("customerfeedbacks")->orderBy('id', 'desc');

        if (!empty($experience = $request->experience)) {
            $feedbacks = $feedbacks->where('type', $experience);
        }

        if (!empty($search = $request->search)) {

            $feedbacks = $feedbacks->whereRaw(
                "(email like ? or message like ?)",
                [
                    "%{$search}%", "%{$search}%"
                ]
            );
        }

        $feedbacks = $feedbacks->paginate($size);

        $total = [
            'happy' => Feedback::where(['vendor_id' => $request->user()->vendor_id, 'experience' => 'happy'])->count('id'),
            'sad' => Feedback::where(['vendor_id' => $request->user()->vendor_id, 'experience' => 'sad'])->count('id'),
            'neutral' => Feedback::where(['vendor_id' => $request->user()->vendor_id, 'experience' => 'neutral'])->count('id')
        ];

        return view('feedback', compact('feedbacks', 'total', 'size', 'experience', 'search'));
    }

    //update feedback

    public function updatefeedback(Request $request, $id){
        $feedback = DB::table("customerfeedbacks")->where("id",$id)->update([
            "resolved" => "true",
        ]);

        return back()->with("msg","Feedback marked as resolved");
    }
}
