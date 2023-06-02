<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serviceName' => 'required',
            'serviceDescription' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $service = new Service();
        $service->name = $request->input('serviceName');
        $service->description = $request->input('serviceDescription');


        $service->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Service added successfully',
        ], 200);
    }
    public function index()
    {
        $service = Service::get();

        return response()->json($service);
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }
    public function getServices($id)
    {
        $service = Service::findOrFail($id);
        return response()->json([
            'name' => $service->name,
            'description' => $service->description,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'serviceName' => 'required',
            'serviceDescription' => 'required',

        ]);

        $service = Service::findOrFail($id);

        $service->name = $validatedData['serviceName'];
        $service->description = $validatedData['serviceDescription'];

        $service->save();

        return response()->json(['message' => 'Service updated successfully'], 200);
    }
}
