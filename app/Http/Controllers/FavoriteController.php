<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Requests\FavoriteRequest;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->with('instructor')->get();

        return response()->json(['favorites' => $favorites]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FavoriteRequest $request)
    {
        $user = auth()->user();
        $instructorId = $request->input('instructor_id');

        // Check if the instructor is already a favorite
        if ($user->favorites()->where('instructor_id', $instructorId)->count() > 0) {
            return response()->json(['message' => 'Instructor is already a favorite.']);
        }

        // Add the instructor to favorites
        $favorite = new Favorite();
        $favorite->user_id = $user->id;
        $favorite->instructor_id = $instructorId;
        $favorite->save();

        return response()->json(['message' => 'Instructor added to favorites.']);
    }

    public function destroy($instructorId)
{
    $user = auth()->user();

    $favorite = $user->favorites()->where('instructor_id', $instructorId)->first();

    if (!$favorite) {
        return response()->json(['message' => 'Favorite not found.'], 404);
    }

    $favorite->delete();

    return response()->json(['message' => 'Favorite instructor removed.']);
}

}
