<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Review;
use App\Models\VendorInfo;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'message' => 'required|string|max:1000',
            'rating.cleanliness' => 'nullable|numeric|min:0|max:5',
            'rating.accuracy' => 'nullable|numeric|min:0|max:5',
            'rating.checkin' => 'nullable|numeric|min:0|max:5',
            'rating.communication' => 'nullable|numeric|min:0|max:5',
            'rating.location' => 'nullable|numeric|min:0|max:5',
            'rating.value' => 'nullable|numeric|min:0|max:5',
        ]);

        try {
            $property = Property::where('id', $request->get('property_id'))->firstOr(function () {
                throw new \Exception('Property not found');
            });

            if (!$property->isReviewable(auth()->user())) {
                return back()->withErrors(['You cannot review this property yet.']);
            }

            $criteria = ['cleanliness', 'accuracy', 'checkin', 'communication', 'location', 'value'];

            $ratings = [];

            foreach ($criteria as $key) {
                $ratings[$key] = $request->input("rating.$key", 0);
            }

            $ratings = array_map('floatval', $ratings);
            $average = count($ratings) ? array_sum($ratings) / count($ratings) : 0;

            Review::updateOrCreate([
                'guest_id' => auth()->id(),
                'property_id' => $property->id,
            ], [
                'host_id' => $property->host_id,
                'comment' => $request->get('message'),
                'rating' => $ratings,
                'avg_rating' => $average,
            ]);
            if ($property->host_id) {
                $hostReviews = Review::where('host_id', $property->host_id)->get();

                $newAvg = $hostReviews->avg('avg_rating');

                $hostInfo = VendorInfo::firstOrNew(['vendor_id' => $property->host_id]);
                $hostInfo->avg_rating = round($newAvg, 2);
                $hostInfo->save();
            }

            return back()->with('success', 'Your review has been submitted');
        } catch (\Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    public function reply(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'message' => 'required|string|max:500',
        ]);

        $review = Review::find($request->review_id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found',
            ]);
        }

        $reply = new Review();
        $reply->review_id = $review->id;
        $reply->property_id = $review->property_id;
        $reply->guest_id = auth()->id();
        $reply->host_id = $review->host_id;
        $reply->rating = $review->rating;
        $reply->avg_rating = $review->avg_rating;
        $reply->comment = $request->message;
        $reply->save();

        return response()->json([
            'success' => true,
            'message' => 'Reply submitted successfully',
        ]);
    }

}
