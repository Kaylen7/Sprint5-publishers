<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServiceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceResource::collection(Service::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data += [
            'user_id' => $data->user_id ?? $request->user()->id,
            'available' => $data->available ?? true
        ];
        $service = Service::create($data);
        if(!$service){
            return response(['message' => 'Service creation failed'], 500);
        }

        return response( new ServiceResource($service), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $uuid)
    {
        $target = Service::where('uuid', $uuid)->firstOrFail();

        if(!$target){
            return response(['message' => 'Service not found'], 404);
        }
        return response(new ServiceResource($target), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $uuid)
    {
        
        $target = Service::where('uuid', $uuid)->firstOrFail();

        if(!$target){
            return response(['message' => 'Service not found'], 404);
        }

        $this->authorize('update', $target);

        $data = $request->validated();
        if(!$data){
            return response([
                "message" => "No content"
            ], 204);
        }

        $target->update($data);

        return response(new ServiceResource($target), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $uuid)
    {
        $target = Service::where('uuid', $uuid)->first();

        $this->authorize('delete', $target);

        $target->delete();
        return response([
            'message' => 'Service removed successfully.'
        ], 200);
    }
}
