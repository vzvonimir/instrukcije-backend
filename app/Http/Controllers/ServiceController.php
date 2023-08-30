<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\AddServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{

    public function index()
    {
        $services = Service::with(['subjects', 'instructors', 'categories'])->get();
        return response()->json([
            'services' => $services,
        ]);
    }

    public function getUserServices()
    {
        $services = Service::where('instructor_id', auth()->user()->id)->with('subjects','instructors','categories')->get();
        if ($services === null) {
            return response()->json(['message' => 'Services was not found.'], 404);
        }
        return response()->json(['services' => $services], 200);
    }

    public function getService($id)
    {
        $service = Service::with('subjects', 'instructors', 'categories')->find($id);
        if ($service === null) {
            return response()->json(['message' => 'Service with the given ID was not found.'], 404);
        }
        return response()->json(['service' => $service], 200);
    }

    public function addService(AddServiceRequest $request)
    {
        $service = new Service;
        $service->instructor_id = auth()->user()->id;
        $service->subject_id = $request->subject_id;
        $service->category_id = $request->category_id;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->save();

        $service->load('subjects', 'instructors', 'categories');
        return response()->json([
            'message' => 'Service successfully added.',
            'service' => $service
        ], 200);
    }
   
    public function updateService(UpdateServiceRequest $request)
    {
        $service = Service::find($request->id);

        $service->price = $request->price;
        $service->description = $request->description;
        $service->subject_id = $request->subject_id;
        $service->category_id = $request->category_id;

        $service->update();

        $service->load('subjects', 'instructors', 'categories');
        return response()->json([
            'message' => 'Service updated successfully!', 
            'service' => $service,
        ], 200);
    }

    public function destroy($id)
    {

        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found!'], 404);
        }

        $service->delete();
        return response()->json(['message' => 'Service deleted successfully!']);
    }



}
