<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RatingRequest;

class RatingController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingRequest $request)
{
    $user = auth()->user();
    $instructorId = $request->instructor_id;

    $existingRating = Rating::where('user_id', $user->id)
        ->where('instructor_id', $instructorId)
        ->first();

    if ($existingRating) {
        $existingRating->rating = $request->rating;
        $existingRating->save();
    } else {
        $newRating = Rating::create([
            'user_id' => $user->id,
            'instructor_id' => $instructorId,
            'rating' => $request->rating
        ]);
    }

    $instructor = User::find($instructorId);
    $ratings = $instructor->ratings; 
    $totalRatings = $ratings->count();
    $averageRating = $ratings->average('rating'); 

    $instructor->total_rating = $totalRatings;
    $instructor->avg_rating = round($averageRating, 2);
    $instructor->save();

    return response()->json([
        'message' => 'Rating saved successfully.',
        'total_rating' => $totalRatings,
        'avg_rating' => $instructor->avg_rating
    ], 200);
}

}
