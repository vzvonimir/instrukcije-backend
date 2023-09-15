<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\FilterRequest;

class SearchController extends Controller
{
    public function search(Request $request)
{
    $searchInput = $request->input('search');

    $services = Service::query()
        ->orWhereHas('instructors', function ($query) use ($searchInput) {
            $query->where('first_name', 'like', "%$searchInput%")
                  ->orWhere('last_name', 'like', "%$searchInput%");
        })
        ->orWhereHas('categories', function ($query) use ($searchInput) {
            $query->where('name', 'like', "%$searchInput%");
        })
        ->orWhereHas('subjects', function ($query) use ($searchInput) {
            $query->where('name', 'like', "%$searchInput%");
        })
        ->with(['subjects', 'instructors', 'categories'])
        ->get();

    return response()->json(['services' => $services], 200);
}

public function filterServices(FilterRequest $request){
    $query = Service::query();

    if ($request->has('categories')) {
        $categories = $request->categories;
        $query->whereHas('categories', function ($q) use ($categories) {
            $q->whereIn('categories.id', $categories);
        });
    }


    $service = $query->with('subjects', 'instructors', 'categories')->get();
    return response()->json(['services' => $service], 200);
}

}
