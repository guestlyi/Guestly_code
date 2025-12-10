<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportAndFeedbackController extends Controller
{
    public function feedbackStore(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string|max:3000',
        ]);

        try {
            $feedback = new Feedback();
            $feedback->user_id = auth()->id();
            $feedback->details = $request->get('feedback');
            $feedback->status = 1;
            $feedback->save();

            return back()->with('success', 'Thanks for your feedback!');
        }catch (\Exception $exception){
            return back()->withErrors($exception->getMessage());
        }
    }
    public function reportStore(Request $request)
    {
        $request->validate([
            'details' => 'required|string|max:5000',
        ]);

        try {
            $report = new Report();
            $report->user_id = auth()->id();
            $report->property_id = $request->property_id;
            $report->name = $request->name;
            $report->report = $request->get('details');
            $report->status = 1;
            $report->save();

            return response()->json(['message' => 'Your report has been submitted!'], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
