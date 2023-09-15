<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::all();
        return response()->json([
            'subjects' => $subjects,
        ]);
    }

    public function getSubject($id)
    {
        $subject = Subject::find($id);
        if ($subject === null) {
            return response()->json(['message' => 'Subject with the given ID was not found.'], 404);
        }
        return response()->json(['subject' => $subject], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->all());
        return response()->json(['message' => 'Subject added successfully.'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request)
    {
        $subject = Subject::find($request->id);
        $subject->update($request->all());
        return response()->json(['message' => 'Subject successfully updated.', 'subject' => $subject], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subject = Subject::find($id);
        if(!$subject){
            return response()->json(['message' => 'Subject not found.'], 404);
        }
        $subject->delete();
        return response()->json(['message' => 'Subject successfully deleted.'], 200);
    }
}
